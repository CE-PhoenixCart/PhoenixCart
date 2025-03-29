<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Paypal Standard Payments
  Basic Paypal Payment Module for Phoenix Cart
  More sophisticated Paypal integration available at https://phoenixcart.org/forum/addons/

  Version 1.4 2025-03-15 Phoenix 1.1.0.0 compatibility. Add invoice prefix to avoid duplicate invoice numbers
  Version 1.3 2025-02-25 Phoenix 1.0.9.9+ compatibility & store comments on order record
  Version 1.2 2024-09-24 Phoenix 1.0.9.6 and php 8.3 compatibility and update common code
  Version 1.1 2024-04-24 Phoenix 1.0.9.1 and php 8.2 compatibility

  author: John Ferguson @BrockleyJohn phoenix@cartmart.uk

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

class paypal_standard extends abstract_payment_module {

  const CONFIG_KEY_BASE = 'MODULE_PAYMENT_PAYPAL_STANDARD_';
  const RETURN_URL = 'checkout_process.php';
  public $form_action_url;
  protected $api;
  protected $customer_comments;

  const ADDON = 'PPSTANDARD';
  const VARIANT = 'CORE';
  const VERSION = '1.4';

  public function __construct() {
    parent::__construct();

    $this->description = sprintf($this->description, Guarantor::ensure_global('Linker')->build(static::RETURN_URL), Guarantor::ensure_global('Linker')->build('ext/modules/payment/paypal_standard_ipn.php'));
    if ( null !== $this->base_constant('STATUS') ) {
      if ( $this->base_constant('GATEWAY') == 'Sandbox' ) {
        $this->title .= ' [Sandbox]';
        $this->public_title .= ' (' . $this->code . '; Sandbox)';
        $this->form_action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
      } else {
        $this->form_action_url = 'https://www.paypal.com/cgi-bin/webscr';
      }
    }

    if ( $this->enabled === true ) {
      if ( Text::is_empty($this->base_constant('PDT_TOKEN')) ) {

        $this->description .= '<div class="alert alert-warning">' . MODULE_PAYMENT_PAYPAL_STANDARD_ERROR_ADMIN_CONFIGURATION_PDT . '</div>';

        $this->enabled = false;
      }
      if ( Text::is_empty($this->base_constant('ID')) ) {
        $this->description .= '<div class="alert alert-warning">' . MODULE_PAYMENT_PAYPAL_STANDARD_ERROR_ADMIN_CONFIGURATION_SELLER . '</div>';

        $this->enabled = false;

      } 
    }

    if ( $this->enabled === true ) {
      if ( isset($order) && is_object($order) ) {
        $this->update_status();
      }
    }

    // Before the stock quantity check is performed in checkout_process.php, detect if the quantity
    // has already been deducted in the IPN to avoid a quantity == 0 redirect
    if ( $this->enabled === true ) {
      if (static::RETURN_URL === basename(Request::get_page())) {
        if ( isset($_SESSION['payment']) && ($_SESSION['payment'] == $this->code) ) {
          $this->pre_before_check();
        }
      }
    }
  }

  public function update_status()
  {
    parent::update_status();
    // paypal posts back to checkout_process so cookie must be samesite none
    if ($this->enabled === true && 'checkout_confirmation.php' === basename(Request::get_page()) && ($_SESSION['payment'] == $this->code)) {
      $options = COOKIE_OPTIONS;
      unset($options['lifetime']);
      if (!isset($options['expires'])) {
        $options['expires'] = strtotime('+1 month');
      }
      $options['secure'] = true;
      $options['samesite'] = 'none';
      Cookie::save('ceid', session_id(), $options);
      if (SESSION_FORCE_COOKIE_USE == 'True') {
        Cookie::save('cookie_test', 'please_accept_for_session', $options);
      }
    }
  }

  protected function extract_order_id() {
    return substr($_SESSION['cart_' . $this->code . '_ID'], strpos($_SESSION['cart_' . $this->code . '_ID'], '-')+1);
  }

  public function selection() {
    if (isset($_SESSION['cart_' . $this->code . '_ID'])) {
      $order_id = $this->extract_order_id();

      $check_query = $GLOBALS['db']->query('SELECT orders_id FROM orders_status_history WHERE orders_id = ' . (int)$order_id . ' LIMIT 1');

      if (mysqli_num_rows($check_query) < 1) {
        order::remove($order_id);
        unset($_SESSION['cart_' . $this->code . '_ID']);
      }
    }

    return parent::selection();
  }

  protected function pre_before_check()
  {
    $result = false;

    $pptx_params = [];

    $seller_accounts = [$this->base_constant('ID')];
    if ( !Text::is_empty($this->base_constant('PRIMARY_ID')) ) {
      $seller_accounts[] = $this->base_constant('PRIMARY_ID');
    }
    
    if ( (isset($_POST['receiver_email']) && in_array($_POST['receiver_email'], $seller_accounts)) || (isset($_POST['business']) && in_array($_POST['business'], $seller_accounts)) ) {
      $parameters = 'cmd=_notify-validate&';

      foreach ( $_POST as $key => $value ) {
        if ( $key != 'cmd' ) {
          $parameters .= $key . '=' . urlencode(stripslashes($value)) . '&';
        }
      }

      $parameters = substr($parameters, 0, -1);

      $result = $this->callAPI($this->form_action_url, $parameters);

      foreach ( $_POST as $key => $value ) {
        $pptx_params[$key] = stripslashes($value);
      }

      foreach ( $_GET as $key => $value ) {
        $pptx_params['GET ' . $key] = stripslashes($value);
      }

      $this->log('PS', '_notify-validate', ($result == 'VERIFIED') ? 1 : -1, $pptx_params, $result, ($this->base_constant('STATUS') == '1') ? 'live' : 'sandbox');
    } elseif ( isset($_GET['tx']) && !Text::is_empty($this->base_constant('PDT_TOKEN')) ) { // PDT
      $pptx_params['cmd'] = '_notify-synch';

      $parameters = 'cmd=_notify-synch&tx=' . urlencode(stripslashes($_GET['tx'])) . '&at=' . urlencode($this->base_constant('PDT_TOKEN'));

      $pdt_raw = $this->callAPI($this->form_action_url, $parameters);

      if ( !empty($pdt_raw) ) {
        $pdt = explode("\n", trim($pdt_raw));

        if ( isset($pdt[0]) ) {
          if ( 'SUCCESS' === $pdt[0] ) {
            $result = 'VERIFIED';

            unset($pdt[0]);
          } else {
            $result = $pdt_raw;
          }
        }

        if ( !empty($pdt) && is_array($pdt) ) {
          foreach ( $pdt as $line ) {
            $p = explode('=', $line, 2);

            if ( count($p) === 2 ) {
              $pptx_params[trim($p[0])] = trim(urldecode($p[1]));
            }
          }
        }
      }

      foreach ( $_GET as $key => $value ) {
        $pptx_params['GET ' . $key] = stripslashes($value);
      }

      $this->log('PS', $pptx_params['cmd'], ($result == 'VERIFIED') ? 1 : -1, $pptx_params, $result, ($this->base_constant('STATUS') == '1') ? 'live' : 'sandbox');

    } else {

      $parameters = 'cmd=_notify-validate&';

      foreach ( $_POST as $key => $value ) {
        if ( $key != 'cmd' ) {
          $parameters .= $key . '=' . urlencode(stripslashes($value)) . '&';
        }
      }

      $parameters = substr($parameters, 0, -1);

      $result = $this->callAPI($this->form_action_url, $parameters);

      foreach ( $_POST as $key => $value ) {
        $pptx_params[$key] = stripslashes($value);
      }

      foreach ( $_GET as $key => $value ) {
        $pptx_params['GET ' . $key] = stripslashes($value);
      }

      $this->log('PS', '_notify-validate', ($result == 'VERIFIED') ? 1 : -1, $pptx_params, $result, ($this->base_constant('STATUS') == '1') ? 'live' : 'sandbox');

    }

    if ($result != 'VERIFIED') {
      if (defined('MODULE_PAYMENT_PAYPAL_STANDARD_TEXT_INVALID_TRANSACTION')) {
        $messageStack->add_session('header', MODULE_PAYMENT_PAYPAL_STANDARD_TEXT_INVALID_TRANSACTION);
      }

      $this->sendDebugEmail($result);

      Href::redirect($GLOBALS['Linker']->build('shopping_cart.php'));
    }

    $this->verifyTransaction($pptx_params);

    $GLOBALS['order_id'] = $this->extract_order_id();

    $check_query = $GLOBALS['db']->query("SELECT orders_status, customer_comments FROM orders WHERE orders_id = " . (int)$GLOBALS['order_id'] . " AND customers_id = " . (int)$_SESSION['customer_id']);

    if (!mysqli_num_rows($check_query) || $GLOBALS['order_id'] != $this->orderid_from_invoice($pptx_params['invoice'] ?? '') || ($_SESSION['customer_id'] != $pptx_params['custom'])) {
      Href::redirect($GLOBALS['Linker']->build('shopping_cart.php'));
    }

    $check = $check_query->fetch_assoc();
    // store comments for later use in history
    $this->customer_comments = $check['customer_comments'];

    // skip before_process() if order was already processed in IPN
    if ( $check['orders_status'] != $this->base_constant('PREPARE_ORDER_STATUS_ID') ) {
      /* if ( !empty($check['customer_comments']) ) {
        $sql_data = [
          'orders_id' => $GLOBALS['order_id'],
          'orders_status_id' => (int)$check['orders_status'],
          'date_added' => 'NOW()',
          'customer_notified' => '0',
          'comments' => $check['customer_comments'],
        ];

        $GLOBALS['db']->perform('orders_status_history', $sql_data);
      } */

      // load the after_process function from the payment modules
      $this->after_process();
    }
  }

  function before_process() {
    $GLOBALS['order']->set_id($this->extract_order_id());

    $GLOBALS['order']->info['order_status'] = DEFAULT_ORDERS_STATUS_ID;
    if ( $this->base_constant('ORDER_STATUS_ID') > 0) {
      $GLOBALS['order']->info['order_status'] = $this->base_constant('ORDER_STATUS_ID');
    }

    $GLOBALS['db']->query("UPDATE orders SET orders_status = " . (int)$GLOBALS['order']->info['order_status'] . ", last_modified = NOW() WHERE orders_id = " . (int)$GLOBALS['order']->get_id());

    $_POST['comments'] = $GLOBALS['order']->info['comments'] = $this->customer_comments;
    $order =& $GLOBALS['order']; // needed for insert history segment
    $GLOBALS['hooks']->register_pipeline('after');

    require 'includes/system/segments/checkout/insert_history.php';

    // load the after_process function from the payment modules
    $this->after_process();
  }

  public function after_process() {
    unset($_SESSION['cart_PayPal_Standard_ID']);

    $GLOBALS['hooks']->register_pipeline('reset');

    Href::redirect($GLOBALS['Linker']->build('checkout_success.php'));
  }

  public function pre_confirmation_check() {
    if (empty($_SESSION['cart']->cartID)) {
      $_SESSION['cartID'] = $_SESSION['cart']->cartID = $_SESSION['cart']->generate_cart_id();
    }
  }

  public function callAPI($url, $parameters) {
    if (! isset($this->api)) {
      $this->api = new paypal_api($this->base_constant('VERIFY_SSL'), $this->base_constant('PROXY'));
    }
    return $this->api->makeCall($url, $parameters);
  }

  public function confirmation() {
    $insert_order = false;
    if (isset($_SESSION['cart_' . $this->code . '_ID'])) {
      $order_id = $this->extract_order_id();

      $curr_check = $GLOBALS['db']->query("SELECT currency FROM orders WHERE orders_id = " . (int)$order_id);
      $curr = $curr_check->fetch_assoc();

      if ( ($curr['currency'] != $GLOBALS['order']->info['currency']) || ($_SESSION['cartID'] != substr($GLOBALS['cart_' . $this->code . '_ID'], 0, strlen($_SESSION['cartID']))) ) {
        $check_query = $GLOBALS['db']->query('SELECT orders_id FROM orders_status_history WHERE orders_id = ' . (int)$order_id . ' LIMIT 1');

        if (mysqli_num_rows($check_query) < 1) {
          order::remove($order_id);
        }

        $insert_order = true;

      } else {
        $GLOBALS['order']->set_id($order_id);
      }
    } else {
      $insert_order = true;
    }

    if ($insert_order) {
      $GLOBALS['order']->info['order_status'] = $this->base_constant('PREPARE_ORDER_STATUS_ID');
      require 'includes/system/segments/checkout/build_order_totals.php';
      require 'includes/system/segments/checkout/insert_order.php';

      $_SESSION['cart_' . $this->code . '_ID'] = $_SESSION['cartID'] . '-' . $GLOBALS['order']->get_id();
    }

    // from 1.0.9.9 comments field is on checkout_confirmation page - update order with comments using ajax on form submit
    $script = <<<EOS
<script>
  const configError = '{$this->base_constant('CONFIG_ERROR')}';
  const commentError = '{$this->base_constant('UPDATE_COMMENT_ERROR')}';
  const commentsField = document.getElementById('inputComments');
  const confirmForm = document.querySelector('form[name="checkout_confirmation"]');
  const confirmButton = (null != confirmForm) ? confirmForm.querySelector('.{$this->base_constant('CONFIRM_BTN')}') : null;
  if (null != confirmForm && null != confirmButton) {
    confirmForm.addEventListener('submit', function(e) {
      e.preventDefault();
      confirmButton.disabled = true;
      const span = confirmButton.querySelector('span');
      span.classList.remove('fa-check-circle', 'fa-exclamation-triangle');
      span.classList.add('fa-spinner', 'fa-spin');
      const comments = (null != commentsField) ? commentsField.value : 'nofield';
      const data = {
        cartid: '{$_SESSION['cartID']}',
        orderid: {$GLOBALS['order']->get_id()},
        comments: comments
      };
      fetch('ext/modules/payment/paypal/checkout_confirmation.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
      .then(response => response.text())
      .then(text => {
        try {
          const data = JSON.parse(text);
          if (data.error) {
            throw new Error(data.error);
          } else if (data.orderid) {
            console.log(data.result);
            confirmForm.submit();
          } else {
            throw new Error(JSON.stringify(data));
            hiterror = true;
          }
        } catch (e) {
          console.error(e);
          throw new Error('invalid response: ' + text);
        }
      })
      .catch(function(err) {
        console.error(err);
        confirmButton.disabled = false;
        span.classList.remove('fa-spinner', 'fa-spin');
        span.classList.add('fa-exclamation-triangle');
        alert(commentError);
      });
    });
  } else {
    alert(configError);
    if (null != confirmForm) {
      confirmForm.addEventListener('submit', function(e) {
        e.preventDefault();
      });
    }
  }
</script>
EOS;
    $parameters = [ 'script' => &$script ];    
    $GLOBALS['hooks']->cat('ppstandardOrderScript', $parameters);
    
    $GLOBALS['Template']->add_block($script, 'footer_scripts');
    return false;
  }

  public function order_comments($indata) {
    $orderid = $this->extract_order_id();
    if ($orderid != $indata['orderid']) {
      return false;
    }
    // 1.0.9.9+ comments from post, previously from order object
    $comments = $GLOBALS['db']->escape($indata['comments'] != 'nofield' ? Text::input($indata['comments']) : $GLOBALS['order']->info['comments'] ?? '');
    // record on order record for later use in history
    $GLOBALS['db']->query("UPDATE orders SET customer_comments = '{$comments}' WHERE orders_id = " . (int)$orderid);
    return $orderid;
  }

  public function log($module, $action, $result, $request, $response, $server, $is_ipn = false) {
    if (!in_array($this->base_constant('LOG_TRANSACTIONS'), ['True', 'False'])
    || (($this->base_constant('LOG_TRANSACTIONS') == 'False') && ($result === 1))) {
      return false;
    }

    $filter = ['ACCT', 'CVV2', 'ISSUENUMBER'];

    if ( is_array($request) ) {
      $request_string = '';
      foreach ( $request as $key => $value ) {
        if ( (strpos($key, '_nh-dns') !== false) || in_array($key, $filter) ) {
          $value = '**********';
        }
        $request_string .= $key . ': ' . $value . "\n";
      }
    } else {
      $request_string = $request;
    }

    if ( is_array($response) ) {
      $response_string = '';
      foreach ( $response as $key => $value ) {
        if ( is_array($value) ) {
          $value = http_build_query($value);
        } elseif ( (strpos($key, '_nh-dns') !== false) || in_array($key, $filter) ) {
          $value = '**********';
        }
        $response_string .= $key . ': ' . $value . "\n";
      }
    } else {
      $response_string = $response;
    }

    $data = [
      'customers_id' => ($_SESSION['customer_id'] ?? 0),
      'module' => $module,
      'action' => $action . (($is_ipn === true) ? ' [IPN]' : ''),
      'result' => $result,
      'server' => ($server === 'live') ? 1 : -1,
      'request' => trim($request_string),
      'response' => trim($response_string),
      'ip_address' => sprintf('%u', ip2long(Request::get_ip())),
      'date_added' => 'NOW()',
    ];

    $GLOBALS['db']->perform('paypal_log', $data);
  }

  public function install($parameter_key = null) {
    parent::install($parameter_key);
    $this->db_check();
  }

  public function keys() {
    $parameters = $this->get_parameters();

    if ($this->check()) {
      $missing_parameters = array_filter($parameters, function ($k) { return !defined($k); }, ARRAY_FILTER_USE_KEY);

      if ($missing_parameters) {
        self::_install($missing_parameters);
        $this->db_check();
      }
    }

    return array_keys($parameters);
  }

  protected function db_check() {
    if (mysqli_num_rows($GLOBALS['db']->query("SHOW TABLES LIKE 'paypal_log'")) != 1) {
      $GLOBALS['db']->query(<<<'EOSQL'
CREATE TABLE paypal_log (
  id int unsigned NOT NULL auto_increment,
  customers_id int NOT NULL,
  module varchar(8) NOT NULL,
  action varchar(255) NOT NULL,
  result tinyint NOT NULL,
  server tinyint NOT NULL,
  request text NOT NULL,
  response text NOT NULL,
  ip_address int unsigned,
  date_added datetime,
  PRIMARY KEY (id),
  KEY idx_oapl_module (module)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

EOSQL
      );
    } 
    if (mysqli_num_rows($GLOBALS['db']->query("SHOW COLUMNS FROM orders WHERE Field = 'customer_comments'")) != 1) {
      $GLOBALS['db']->query('ALTER TABLE orders ADD customer_comments TEXT');
    }

  }

  public function process_button() {
    $total_tax = $GLOBALS['order']->info['tax'];
    // prevent double-counting shipping tax
    if (isset($_SESSION['shipping']['cost'])) {
      $total_tax = $GLOBALS['order']->info['tax'] - ($GLOBALS['order']->info['shipping_cost'] - $_SESSION['shipping']['cost']);
    }

    $ipn_language = null;

    $lng = new language();

    if ( count($lng->catalog_languages) > 1 ) {
      foreach ( $lng->catalog_languages as $key => $value ) {
        if ( $value['directory'] == $_SESSION['language'] ) {
          $ipn_language = $key;
          break;
        }
      }
    }

    $notify_url = $GLOBALS['Linker']->build('ext/modules/payment/paypal/standard_ipn.php', [], false);
    if (isset($ipn_language)) {
      $notify_url->set_parameter('language', $ipn_language);
    }
    $amount = ($GLOBALS['order']->info['total'] - $GLOBALS['order']->info['shipping_cost'] - $total_tax);
    $amount = $GLOBALS['currencies']->format_raw($amount);

    $order_id = $this->extract_order_id();
    $process_button_string = '';
    $params = [
      'cmd' => '_cart',
      'upload' => '1',
      'item_name_1' => STORE_NAME,
      'shipping_1' => $GLOBALS['currencies']->format_raw($GLOBALS['order']->info['shipping_cost']),
      'business' => $this->base_constant('ID'),
      'amount_1' => $amount,
      'currency_code' => $GLOBALS['currency'],
      'invoice' => $this->base_constant('INVOICE_PREFIX') . $order_id,
      'custom' => $_SESSION['customer_id'],
      'no_note' => '1',
      'notify_url' => $GLOBALS['Linker']->build('ext/modules/payment/paypal/standard_ipn.php'),
      'rm' => '2',
      'return' => $GLOBALS['Linker']->build(static::RETURN_URL),
      'cancel_return' => $GLOBALS['Linker']->build('checkout_payment.php'),
      'bn' => 'CEPHX_PS',
      'paymentaction' => ($this->base_constant('TRANSACTION_METHOD') == 'Sale' ? 'sale' : 'authorization')
    ];

    if ((! Text::is_empty($this->base_constant('TEXT_RETURN_BUTTON'))) && strlen($this->base_constant('TEXT_RETURN_BUTTON')) <= 60 ) {
      $params['cbt'] = $this->base_constant('TEXT_RETURN_BUTTON');
    }
    if (is_numeric($_SESSION['sendto']) && ($_SESSION['sendto'] > 0)) {
      $parameters['address_override'] = '1';
      $GLOBALS['customer_data']->get('country', $GLOBALS['order']->delivery);
      $parameters['first_name'] = $GLOBALS['customer_data']->get('firstname', $GLOBALS['order']->delivery);
      $parameters['last_name'] = $GLOBALS['customer_data']->get('lastname', $GLOBALS['order']->delivery);
      $parameters['address1'] = $GLOBALS['customer_data']->get('street_address', $GLOBALS['order']->delivery);
      $parameters['address2'] = $GLOBALS['customer_data']->get('suburb', $GLOBALS['order']->delivery);
      $parameters['city'] = $GLOBALS['customer_data']->get('city', $GLOBALS['order']->delivery);
      $parameters['state'] = Zone::fetch_code(
        $GLOBALS['customer_data']->get('zone_id', $GLOBALS['order']->delivery),
        $GLOBALS['customer_data']->get('country_id', $GLOBALS['order']->delivery),
        $GLOBALS['customer_data']->get('state', $GLOBALS['order']->delivery));
      $parameters['zip'] = $GLOBALS['customer_data']->get('postcode', $GLOBALS['order']->delivery);
      $parameters['country'] = $GLOBALS['customer_data']->get('country_iso_code_2', $GLOBALS['order']->delivery);
    } else {
      $parameters['no_shipping'] = '1';
      $GLOBALS['customer_data']->get('country', $GLOBALS['order']->billing);
      $parameters['first_name'] = $GLOBALS['customer_data']->get('firstname', $GLOBALS['order']->billing);
      $parameters['last_name'] = $GLOBALS['customer_data']->get('lastname', $GLOBALS['order']->billing);
      $parameters['address1'] = $GLOBALS['customer_data']->get('street_address', $GLOBALS['order']->billing);
      $parameters['address2'] = $GLOBALS['customer_data']->get('suburb', $GLOBALS['order']->billing);
      $parameters['city'] = $GLOBALS['customer_data']->get('city', $GLOBALS['order']->billing);
      $parameters['state'] = Zone::fetch_code(
        $GLOBALS['customer_data']->get('zone_id', $GLOBALS['order']->billing),
        $GLOBALS['customer_data']->get('country_id', $GLOBALS['order']->billing),
        $GLOBALS['customer_data']->get('state', $GLOBALS['order']->billing));
      $parameters['zip'] = $GLOBALS['customer_data']->get('postcode', $GLOBALS['order']->billing);
      $parameters['country'] = $GLOBALS['customer_data']->get('country_iso_code_2', $GLOBALS['order']->billing);
    }

    $item_params = [];

    $line_item_no = 1;

    foreach ($GLOBALS['order']->products as $product) {
      if ( DISPLAY_PRICE_WITH_TAX == 'true' ) {
        $product_price = $GLOBALS['currencies']->format_raw($product['final_price'] + Tax::calculate($product['final_price'], $product['tax']));
      } else {
        $product_price = $GLOBALS['currencies']->format_raw($product['final_price']);
      }

      $item_params['item_name_' . $line_item_no] = $product['name'];
      $item_params['amount_' . $line_item_no] = $product_price;
      $item_params['quantity_' . $line_item_no] = $product['qty'];

      $line_item_no++;
    }

    $items_total = $GLOBALS['currencies']->format_raw($GLOBALS['order']->info['subtotal']);

    $has_negative_price = false;

    // order totals are processed on checkout confirmation but not captured into a variable
    foreach (($GLOBALS['order_total_modules']->modules ?? []) as $value) {
      $class = pathinfo($value, PATHINFO_FILENAME);

      if ($GLOBALS[$class]->enabled) {
        foreach ($GLOBALS[$class]->output as $order_total) {
          if (!Text::is_empty($order_total['title']) && !Text::is_empty($order_total['text'])) {
            if ( !in_array($GLOBALS[$class]->code, ['ot_subtotal', 'ot_shipping', 'ot_tax', 'ot_total']) ) {
              $item_params['item_name_' . $line_item_no] = $order_total['title'];
              $item_params['amount_' . $line_item_no] = $GLOBALS['currencies']->format_raw($order_total['value']);

              $items_total += $item_params['amount_' . $line_item_no];

              if ( $item_params['amount_' . $line_item_no] < 0 ) {
                $has_negative_price = true;
              }

              $line_item_no++;
            }
          }
        }
      }
    }

    $paypal_item_total = $items_total + $params['shipping_1'];

    if ( DISPLAY_PRICE_WITH_TAX == 'false' ) {
      $item_params['tax_cart'] = $GLOBALS['currencies']->format_raw($total_tax);

      $paypal_item_total += $item_params['tax_cart'];
    }

    if ( ($has_negative_price == false) && ($GLOBALS['currencies']->format_raw($paypal_item_total) == $GLOBALS['currencies']->format_raw($GLOBALS['order']->info['total'])) ) {
      $params = array_merge($params, $item_params);
    } else {
      $params['tax_cart'] = $GLOBALS['currencies']->format_raw($total_tax);
    }

    foreach ($params as $key => $value) {
      $process_button_string .= new Input($key, ['value' => $value], 'hidden');
    }

    return $process_button_string;
  }

  public function orderid_from_invoice($invoice) {
    if (strlen($invoice) > strlen($this->base_constant('INVOICE_PREFIX'))) {
      return substr($invoice, strlen($this->base_constant('INVOICE_PREFIX')));
    }
    return $invoice;
  }

  function verifyTransaction($pptx_params, $is_ipn = false) {
    $pptx_orderid = $this->orderid_from_invoice($pptx_params['invoice'] ?? '');
    if ( is_numeric($pptx_orderid) && ($pptx_orderid > 0) && isset($pptx_params['custom']) && is_numeric($pptx_params['custom']) && ($pptx_params['custom'] > 0) ) {
      $order_query = $GLOBALS['db']->query("SELECT orders_id, currency, currency_value FROM orders WHERE orders_id = " . (int)$pptx_orderid . " AND customers_id = " . (int)$pptx_params['custom']);

      if ( mysqli_num_rows($order_query) === 1 ) {
        $order = $order_query->fetch_assoc();

        $total_query = $GLOBALS['db']->query("SELECT value FROM orders_total WHERE orders_id = " . (int)$order['orders_id'] . " AND class = 'ot_total' limit 1");
        $total = $total_query->fetch_assoc();

        $comment_status = 'Transaction ID: ' . htmlspecialchars($pptx_params['txn_id']) . "\n"
                        . 'Payer Status: ' . htmlspecialchars($pptx_params['payer_status']) . "\n"
                        . 'Address Status: ' . htmlspecialchars($pptx_params['address_status']) . "\n"
                        . 'Payment Status: ' . htmlspecialchars($pptx_params['payment_status']) . "\n"
                        . 'Payment Type: ' . htmlspecialchars($pptx_params['payment_type']) . "\n"
                        . 'Pending Reason: ' . htmlspecialchars($pptx_params['pending_reason'] ?? '');

        if ( $pptx_params['mc_gross'] != $GLOBALS['currencies']->format_raw($total['value'], true, $order['currency'], $order['currency_value']) ) {
          $comment_status .= "\n" . 'Error Total Mismatch: PayPal transaction value (' . htmlspecialchars($pptx_params['mc_gross']) . ') does not match order value (' . $GLOBALS['currencies']->format_raw($total['value'], true, $order['currency'], $order['currency_value']) . ')';
        }

        if ( $is_ipn === true ) {
          $comment_status .= "\n" . 'Source: IPN';
        }

        $sql_data = [
          'orders_id' => (int)$order['orders_id'],
          'orders_status_id' => $this->base_constant('TRANSACTIONS_ORDER_STATUS_ID'),
          'date_added' => 'NOW()',
          'customer_notified' => '0',
          'comments' => $comment_status,
        ];

        $GLOBALS['db']->perform('orders_status_history', $sql_data);
      }
    }
  }

  protected function get_parameters() {
    $params = [
      static::CONFIG_KEY_BASE . 'STATUS' => [
        'title' => 'Enable Paypal Standard',
        'desc' => 'Do you want to accept payments with the module?',
        'value' => 'True',
        'set_func' => "Config::select_one(['True', 'False'], ",
      ],
      static::CONFIG_KEY_BASE . 'ID' => [
        'title' => 'Seller Email',
        'desc' => 'The paypal-registered email for which you are accepting payments',
      ],
      static::CONFIG_KEY_BASE . 'PRIMARY_ID' => [
        'title' => 'Primary Paypal Email',
        'desc' => 'Leave empty if the seller email is the main paypal email. If they are different put the main email here for IPN validation',
      ],
      static::CONFIG_KEY_BASE . 'PDT_TOKEN' => [
        'title' => 'PDT Identity Token',
        'desc' => 'Your Payment Data Transfer (PDT) Identity Token. Copy from your Paypal account Website Payment Preferences page. Used to verify transactions and help prevent your payments being hijacked.',
      ],
      static::CONFIG_KEY_BASE . 'TRANSACTION_METHOD' => [
        'title' => 'Transaction Method',
        'desc' => 'The processing method to use for each transaction.',
        'value' => 'Sale',
        'set_func' => "Config::select_one(['Authorization', 'Sale'], ",
      ],
      static::CONFIG_KEY_BASE . 'PREPARE_ORDER_STATUS_ID' => [
        'title' => 'Set Preparing Order Status',
        'desc' => 'Set the status of prepared orders made with this payment module to this value',
        'value' => abstract_payment_module::ensure_order_status(static::CONFIG_KEY_BASE . 'PREPARE_ORDER_STATUS_ID', 'Preparing [Paypal Standard]'),
        'use_func' => 'order_status::fetch_name',
        'set_func' => 'Config::select_order_status(',
      ],
      static::CONFIG_KEY_BASE . 'ORDER_STATUS_ID' => [
        'title' => 'Set Order Status',
        'desc' => 'Set the status of orders made with this payment module to this value',
        'value' => '0',
        'use_func' => 'order_status::fetch_name',
        'set_func' => 'Config::select_order_status(',
      ],
      static::CONFIG_KEY_BASE . 'TRANSACTIONS_ORDER_STATUS_ID' => [
        'title' => 'Transactions Order Status Level',
        'desc' => 'Include transaction information in this order status level.',
        'value' => abstract_payment_module::ensure_order_status(static::CONFIG_KEY_BASE . 'TRANSACTIONS_ORDER_STATUS_ID', 'Paypal [Transactions]'),
        'use_func' => 'order_status::fetch_name',
        'set_func' => 'Config::select_order_status(',
      ],
      static::CONFIG_KEY_BASE . 'ZONE' => [
        'title' => 'Payment Zone',
        'desc' => 'If a zone is selected, only enable this payment method for that zone.',
        'value' => '0',
        'use_func' => 'geo_zone::fetch_name',
        'set_func' => 'Config::select_geo_zone(',
      ],
      static::CONFIG_KEY_BASE . 'GATEWAY' => [
        'title' => 'Paypal Environment',
        'desc' => 'Use the testing (Sandbox) environment or Live at Paypal?',
        'value' => 'Live',
        'set_func' => "Config::select_one(['Live', 'Sandbox'], ",
      ],
      /* static::CONFIG_KEY_BASE . 'VERIFY_SSL' => [
        'title' => 'Verify SSL Certificate',
        'desc' => 'Verify the gateway server SSL certificate on connection?',
        'value' => 'True',
        'set_func' => "Config::select_one(['True', 'False'], ",
      ],
      static::CONFIG_KEY_BASE . 'PROXY' => [
        'title' => 'Proxy Server',
        'desc' => 'A few installations need to send API requests via a proxy server. Configure it here, e.g. 123.45.67.89:8080',
      ], */
      static::CONFIG_KEY_BASE . 'CONFIRM_BTN' => [
        'title' => 'Confirm Button',
        'desc' => 'Class of submit button on checkout confirmation page',
        'value' => 'btn-success',
      ], 
      static::CONFIG_KEY_BASE . 'INVOICE_PREFIX' => [
        'title' => 'Invoice Prefix',
        'desc' => 'If sending payments from multiple stores, use a prefix to make invoice refs unique. Added before the order ID.',
        'value' => '',
      ], 
      static::CONFIG_KEY_BASE . 'LOG_TRANSACTIONS' => [
        'title' => 'Log Transactions',
        'desc' => 'Log details of transactions',
        'value' => 'True',
        'set_func' => "Config::select_one(['True', 'False'], ",
      ], 
      static::CONFIG_KEY_BASE . 'DEBUG_EMAIL' => [
        'title' => 'Debug E-Mail Address',
        'desc' => 'All parameters of an invalid transaction will be sent to this email address if one is entered.',
      ],
      static::CONFIG_KEY_BASE . 'SORT_ORDER' => [
        'title' => 'Sort order of display.',
        'desc' => 'Sort order of display. Lowest is displayed first.',
        'value' => '0',
      ],
    ];

    return $params;
  }

  public function sendDebugEmail($response = []) {
    if (!Text::is_empty(constant(static::CONFIG_KEY_BASE . 'DEBUG_EMAIL'))) {
      $email_body = '';

      if (!empty($response)) {
        $email_body .= 'RESPONSE:' . "\n\n" . print_r($response, true) . "\n\n";
      }

      if (!empty($_POST)) {
        $email_body .= '$_POST:' . "\n\n" . print_r($_POST, true) . "\n\n";
      }

      if (!empty($_GET)) {
        $email_body .= '$_GET:' . "\n\n" . print_r($_GET, true) . "\n\n";
      }

      if (!empty($email_body)) {
        Notifications::mail('', constant(static::CONFIG_KEY_BASE . 'DEBUG_EMAIL'), $this->code . ' Debug E-Mail', trim($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
    }
  }

}