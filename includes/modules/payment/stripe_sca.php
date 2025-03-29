<?php
/*
* $Id: stripe_sca.php
* $Loc: /includes/modules/payment/
*
* Name: StripeSCA
* Version: 1.70
* Release Date: 2025-03-02
* Author: Rainer Schmied
* 	 phoenixcartaddonsaddons.com / raiwa@phoenixcartaddons.com
*
* License: Released under the GNU General Public License
*
* Comments: Author: [Rainer Schmied @raiwa]
* Author URI: [www.phoenixcartaddons.com]
* 
* CE Phoenix, E-Commerce made Easy
* https://phoenixcart.org
* 
* Copyright (c) 2021 Phoenix Cart
* 
* 
*/

  require_once DIR_FS_CATALOG . 'includes/apps/stripe_sca/init.php';

  class stripe_sca extends abstract_payment_module {

    const CONFIG_KEY_BASE = 'MODULE_PAYMENT_STRIPE_SCA_';
    const REQUIRES = [ 'name', 'street_address', 'postcode', 'city', 'country', 'email_address', 'id' ];

    public $intent;
    private $signature = 'stripe|stripe_sca|1.6.0|2.3';
    public $api_version = '2022-11-15';

    function __construct() {
      global $order, $payment;

      parent::__construct();
      $this->order_status = defined('MODULE_PAYMENT_STRIPE_SCA_PREPARE_ORDER_STATUS_ID') && ((int) MODULE_PAYMENT_STRIPE_SCA_PREPARE_ORDER_STATUS_ID > 0) ? (int) MODULE_PAYMENT_STRIPE_SCA_PREPARE_ORDER_STATUS_ID : 0;

      if (defined('MODULE_PAYMENT_STRIPE_SCA_STATUS')) {
        if (MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Test') {
          $this->title .= ' [Test]';
          $this->public_title .= ' (Test)';
        }

        $this->description .= $this->getTestLinkInfo();
      }

      if (!function_exists('curl_init')) {
        $this->description = '<div class="alert alert-warning">' . MODULE_PAYMENT_STRIPE_SCA_ERROR_ADMIN_CURL . '</div>' . $this->description;

        $this->enabled = false;
      }

      if ($this->enabled === true) {
        if ((MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Live' && (Text::is_empty(MODULE_PAYMENT_STRIPE_SCA_LIVE_PUBLISHABLE_KEY) || Text::is_empty(MODULE_PAYMENT_STRIPE_SCA_LIVE_SECRET_KEY))) || (MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Test' && (Text::is_empty(MODULE_PAYMENT_STRIPE_SCA_TEST_PUBLISHABLE_KEY) || Text::is_empty(MODULE_PAYMENT_STRIPE_SCA_TEST_SECRET_KEY)))) {
          $this->description = '<div class="alert alert-warning">' . MODULE_PAYMENT_STRIPE_SCA_ERROR_ADMIN_CONFIGURATION . '</div>' . $this->description;

          $this->enabled = false;
        } elseif (isset($order) && $order instanceof order) {
          $this->update_status();
        }
      }

      if (('modules.php' === $GLOBALS['PHP_SELF']) && ('install' === ($_GET['action'] ?? null)) && ('conntest' === ($_GET['subaction'] ?? null))) {
          echo $this->getTestConnectionResult();
          exit;
      }
    }

    private function extract_order_id() {
      return substr($_SESSION['cart_Stripe_SCA_ID'], strpos($_SESSION['cart_Stripe_SCA_ID'], '-')+1);
    }

    function selection() {
      if ((MODULE_PAYMENT_STRIPE_SCA_TOKENS == 'True') && !isset($_SESSION['payment'])) {
        $tokens_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT 1
  FROM customers_stripe_tokens
  WHERE customers_id = %s
  LIMIT 1
EOSQL
          , (int)$_SESSION['customer_id']));


        if (mysqli_num_rows($tokens_query)) {
          $_SESSION['payment'] = $this->code;
        }
      }

      return [
        'id' => $this->code,
        'module' => $this->public_title,
      ];
    }

    function pre_confirmation_check() {

      if (MODULE_PAYMENT_STRIPE_SCA_CARD_DATA_ONE_LINE == 'True') {
        $GLOBALS['Template']->add_block($this->getSubmitCardDetailsOnelineJavascript(), 'footer_scripts');
      } else {
        $GLOBALS['Template']->add_block($this->getSubmitCardDetailsMultilineJavascript(), 'footer_scripts');
      }
    }

    function confirmation() {
      global $languages_id, $order, $currency, $shipping, $db;

      if (isset($_SESSION['cartID'])) {
        if (isset($_SESSION['cart_Stripe_SCA_ID'])) {
          $order_id = $this->extract_order_id();

          $check_query = $db->query(sprintf(<<<'EOSQL'
SELECT orders_id
  FROM orders
  WHERE orders_id = %s
  LIMIT 1
EOSQL
          , (int)$order_id));

          if (mysqli_num_rows($check_query)) {
            order::remove($order_id, false);
          }
        }

        if (isset($order->info['payment_method_raw'])) {
            $order->info['payment_method'] = $order->info['payment_method_raw'];
            unset($order->info['payment_method_raw']);
        }

        $GLOBALS['customer_notification'] = 0;

        require 'includes/system/segments/checkout/build_order_totals.php';
        require 'includes/system/segments/checkout/insert_order.php';
        require 'includes/system/segments/checkout/insert_history.php';

        $_SESSION['cart_Stripe_SCA_ID'] = $_SESSION['cartID'] . '-' . $order_id;
      }

      $secret_key = MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Live' ? MODULE_PAYMENT_STRIPE_SCA_LIVE_SECRET_KEY : MODULE_PAYMENT_STRIPE_SCA_TEST_SECRET_KEY;
      \Stripe\Stripe::setApiKey($secret_key);
      \Stripe\Stripe::setApiVersion($this->api_version);

      $metadata = [
        'customer_id' => Text::output($_SESSION['customer_id']),
        'order_id' => Text::output($order_id),
        'company' => isset($order->customer['company'])? Text::output($order->customer['company']) : '',
      ];

      $content = '';

      if (MODULE_PAYMENT_STRIPE_SCA_TOKENS == 'True') {
        $tokens_query = $db->query(sprintf(<<<'EOSQL'
SELECT id, stripe_token, card_type, number_filtered, expiry_date
  FROM customers_stripe_tokens
  WHERE customers_id = %s
  ORDER BY date_added
EOSQL
          , (int)$_SESSION['customer_id']));


        if (mysqli_num_rows($tokens_query) > 0) {
          $content .= '<table id="stripe_table" border="0" width="100%" cellspacing="0" cellpadding="2">';

          while ($tokens = $tokens_query->fetch_assoc()) {
              // default to charging first saved card, changed by client directly calling payment_intent.php hook as selection changed
              $content .= '<tr class="moduleRow" id="stripe_card_' . (int) $tokens['id'] . '">' .
                      '  <td width="40" valign="top"><input type="radio" name="stripe_card" value="' . (int) $tokens['id'] . '" /></td>' .
                      '  <td valign="top"><strong>' . htmlspecialchars($tokens['card_type']) . '</strong>&nbsp;&nbsp;****' . htmlspecialchars($tokens['number_filtered']) . '&nbsp;&nbsp;' . htmlspecialchars(substr($tokens['expiry_date'], 0, 2) . '/' . substr($tokens['expiry_date'], 2)) . '</td>' .
                      '</tr>';
          }

          $content .= '<tr class="moduleRow" id="stripe_card_0">' .
                  '  <td width="40" valign="top"><input type="radio" name="stripe_card" value="0" /></td>' .
                  '  <td valign="top">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_NEW . '</td>' .
                  '</tr>' .
                  '</table><div id="save-card-element"></div>';
        }
      }
      
      if (MODULE_PAYMENT_STRIPE_SCA_CARD_DATA_ONE_LINE == 'True') {
        $content .= '<div id="stripe_table_new_card">' .
                    '<div class="form-group mb-3"><label for="cardholder-name" class="control-label">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_OWNER . '</label>' .
                    '<div class="col-sm-16"><input type="text" id="cardholder-name" class="form-control" value="' . Text::output($order->billing['name']) . '" required></text></div></div>' .
                    '<div class="form-group mb-3"><label for="card-element" class="control-label">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_TYPE . '</label>' .
                    '<div id="card-element" class="col-sm-16"></div></div>';
                               
        if (MODULE_PAYMENT_STRIPE_SCA_TOKENS == 'True') {
          $content .= '<div class="form-check">' . 
                        (new Tickable('card-save', ['value' => '1'], 'checkbox'))->append_css('form-check-input')->set('id', 'inputCardSave') . '
                        <label class="form-check-label" for="inputCardSave">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_SAVE . '</label>
                       </div>';
        }
      } else {
        $content .= '<div id="stripe_table_new_card">' .
                      '<div class="form-group row mb-3">
                        <label for="cardholder-name" class="col-form-label col-sm-4 ms-4 ms-sm-0 pe-0 text-start text-sm-end">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_OWNER . '</label>' .
                      ' <div class="col-sm-8 ms-3 ms-sm-0 cardholder"><input type="text" id="cardholder-name" class="form-control" value="' . Text::output($order->billing['name']) . '" required></text></div>
                       </div>
                       <div class="form-group row ms-3 me-1 mb-3">
                         <label for="card-number" class="col-form-label col-sm-4 text-start text-sm-end">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_NUMBER . '</label>' .
                      '  <div id="card-number" class="col-sm-8 card-details"></div>
                       </div>
                       <div class="form-group row ms-3 me-1 mb-3">
                         <label for="card-expiry" class="col-form-label col-sm-4 text-start text-sm-end">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_EXPIRY . '</label>' .
                      '  <div id="card-expiry" class="col-sm-8 card-details"></div>
                       </div>
                       <div class="form-group row ms-3 me-1 mb-3">
                         <label for="card-cvc" class="col-form-label col-sm-4 text-start text-sm-end">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_CVC . '</label>' .
                      '  <div id="card-cvc" class="col-sm-8 card-details"></div>
                       </div>';
        if (MODULE_PAYMENT_STRIPE_SCA_TOKENS == 'True') {
          $content .= '<div class="form-check col-sm-8 offset-4 ps-5">' . 
                        (new Tickable('card-save', ['value' => '1'], 'checkbox'))->append_css('form-check-input')->set('id', 'inputCardSave') . '
                        <label class="form-check-label" for="inputCardSave">' . MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_SAVE . '</label>
                       </div>';
        }
      }
      $content .= '</div><div id="card-errors" role="alert" class="messageStackError payment-errors"></div>';

      $address = [
        'address_line1' => $GLOBALS['customer_data']->get('street_address', $order->billing),
        'address_city' => $GLOBALS['customer_data']->get('city', $order->billing),
        'address_zip' => $GLOBALS['customer_data']->get('postcode', $order->billing),
        'address_state' => $GLOBALS['customer_data']->get('state', $order->billing),
        'address_country' => $GLOBALS['customer_data']->get('country_iso_code_2', $order->billing),
      ];

      foreach ($address as $k => $v) {
          $content .= '<input type="hidden" id="' . Text::output($k) . '" value="' . Text::output($v ?? '') . '" />';
      }
      $content .= '<input type="hidden" id="email_address" value="' . Text::output($GLOBALS['customer_data']->get('email_address', $order->customer)) . '" />';
      $content .= '<input type="hidden" id="customer_id" value="' . Text::output($_SESSION['customer_id']) . '" />';

      if (MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_METHOD == 'Capture') {
          $capture_method = 'automatic';
      } else {
          $capture_method = 'manual';
      }
      $customer_mail = Text::output($GLOBALS['customer_data']->get('email_address', $order->customer));
      // have to create intent before loading the javascript because it needs the intent id
      if (isset($_SESSION['stripe_payment_intent_id'])) {
        try {
            $this->intent = \Stripe\PaymentIntent::retrieve(['id' => $_SESSION['stripe_payment_intent_id']]);
            $this->event_log($_SESSION['customer_id'], 'page retrieve intent', $_SESSION['stripe_payment_intent_id'], $this->intent);
            $this->intent->amount = $this->format_raw($order->info['total']);
            $this->intent->currency = $currency;
            $this->intent->receipt_email = $customer_mail;
            $this->intent->metadata = $metadata;
            $response = $this->intent->save();
        } catch (exception $err) {
            $this->event_log($_SESSION['customer_id'], 'page create intent', $_SESSION['stripe_payment_intent_id'], $err->getMessage());
            // failed to save existing intent, so create new one
            unset($_SESSION['stripe_payment_intent_id']);
        }
      }
      if (!isset($_SESSION['stripe_payment_intent_id'])) {
        $params = [
          'amount' => $this->format_raw($order->info['total']),
          'currency' => $currency,
          'receipt_email' => $customer_mail,
          'setup_future_usage' => 'off_session',
          'capture_method' => $capture_method,
          'metadata' => $metadata,
        ];
        $this->intent = \Stripe\PaymentIntent::create($params);
        $this->event_log($_SESSION['customer_id'], 'page create intent', json_encode($params), $this->intent);
        $_SESSION['stripe_payment_intent_id'] = $this->intent->id;
      }
      $content .= '<input type="hidden" id="intent_id" value="' . Text::output($_SESSION['stripe_payment_intent_id']) . '" />' .
              '<input type="hidden" id="secret" value="' . Text::output($this->intent->client_secret) . '" />';

      $confirmation = ['title' => $content];

      return $confirmation;
    }

    function before_process() {

      $this->after_process();
    }

    function after_process() {

      if (isset($_SESSION['cart_Stripe_SCA_ID'])) {
        $GLOBALS['order']->set_id($this->extract_order_id());
        $GLOBALS['hooks']->register_pipeline('after');

        $GLOBALS['hooks']->register_pipeline('reset');
        unset($_SESSION['stripe_error']);
        unset($_SESSION['stripe_payment_intent_id']);
        unset($_SESSION['cart_Stripe_SCA_ID']);

        Href::redirect($GLOBALS['Linker']->build('checkout_success.php'));
      }
    }

    function get_error() {
      global $stripe_error;

      $message = MODULE_PAYMENT_STRIPE_SCA_ERROR_GENERAL;

      if (isset($_SESSION['stripe_error'])) {
        $message = $stripe_error . ' ' . $message;

        unset($_SESSION['stripe_error']);
      }

      if (!empty($_GET['error'])) {
        switch ($_GET['error']) {
          case 'cardstored':
            $message = MODULE_PAYMENT_STRIPE_SCA_ERROR_CARDSTORED;
            break;
        }
      }

      $error = [
        'title' => MODULE_PAYMENT_STRIPE_SCA_ERROR_TITLE,
        'error' => $message,
      ];

      return $error;
    }

    function event_log($customer_id, $action, $request, $response) {
      global $db;

      if (MODULE_PAYMENT_STRIPE_SCA_LOG == 'True') {
        $request = $request?? '';
        $response = $response?? '';

        $db->query(sprintf(<<<'EOSQL'
INSERT into stripe_event_log (customer_id, action, request, response, date_added)
  VALUES ('%s', '%s', '%s', '%s', now())
EOSQL
        , (int)$customer_id, $action, $db->escape($request), $db->escape($response)));

      }
    }

    function get_parameters() {
      global $db;

      if (mysqli_num_rows($db->query("SHOW TABLES LIKE 'customers_stripe_tokens'")) != 1) {
        $sql = <<<EOD
CREATE TABLE customers_stripe_tokens (
  id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  stripe_token varchar(255) NOT NULL,
  card_type varchar(32) NOT NULL,
  number_filtered varchar(20) NOT NULL,
  expiry_date char(6) NOT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (id),
  KEY idx_cstripet_customers_id (customers_id),
  KEY idx_cstripet_token (stripe_token)
);
EOD;

          $db->query($sql);
      }
      if (mysqli_num_rows($db->query("SHOW TABLES LIKE 'stripe_event_log'")) != 1) {
        $sql = <<<EOD
CREATE TABLE stripe_event_log (
  id int NOT NULL auto_increment,
  customer_id int NOT NULL,
  action varchar(255) NOT NULL,
  request varchar(255) NOT NULL,
  response varchar(255) NOT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (id)
);
EOD;

        $db->query($sql);
      }

      $params = [
        'MODULE_PAYMENT_STRIPE_SCA_STATUS' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_STATUS_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_STATUS_DESC,
          'value' => 'True',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_SERVER_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_SERVER_DESC,
          'value' => 'Live',
          'set_func' => "Config::select_one(['Live', 'Test'], ",
        ],
        'MODULE_PAYMENT_STRIPE_SCA_LIVE_PUBLISHABLE_KEY' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_PUB_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_PUB_DESC,
          'value' => '',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_LIVE_SECRET_KEY' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_SECRET_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_SECRET_DESC,
          'value' => '',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_LIVE_WEBHOOK_SECRET' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_WEBHOOK_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_WEBHOOK_DESC,
          'value' => '',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_TEST_PUBLISHABLE_KEY' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_PUB_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_PUB_DESC,
          'value' => '',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_TEST_SECRET_KEY' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_SECRET_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_SECRET_DESC,
          'value' => ''],
        'MODULE_PAYMENT_STRIPE_SCA_TEST_WEBHOOK_SECRET' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_WEBHOOK_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_WEBHOOK_DESC,
          'value' => '',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_TOKENS' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TOKENS_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TOKENS_DESC,
          'value' => 'False',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_STRIPE_SCA_CARD_DATA_ONE_LINE' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_CARD_DATA_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_CARD_DATA_DESC,
          'value' => 'False',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_STRIPE_SCA_LOG' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_LOG_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_LOG_DESC,
          'value' => 'False',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_METHOD' => ['title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_METHOD_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_METHOD_DESC,
          'value' => 'Capture',
          'set_func' => "Config::select_one(['Authorize', 'Capture'], ",
        ],
        'MODULE_PAYMENT_STRIPE_SCA_PREPARE_ORDER_STATUS_ID' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_NEW_ORDER_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_NEW_ORDER_DESC,
          'value' => self::ensure_order_status('MODULE_PAYMENT_STRIPE_SCA_PREPARE_ORDER_STATUS_ID', 'Preparing [Stripe SCA]'),
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_ORDER_STATUS_ID' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_PROCESSED_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_PROCESSED_DESC,
          'value' => '0',
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_ORDER_STATUS_ID' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TRANSACTION_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_TRANSACTION_DESC,
          'value' => self::ensure_order_status('MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_ORDER_STATUS_ID', 'Stripe SCA [Transactions]'),
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_ZONE' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_ZONE_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_ZONE_DESC,
          'value' => '0',
          'use_func' => 'geo_zone::fetch_name',
          'set_func' => 'Config::select_geo_zone(',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_VERIFY_SSL' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_SSL_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_SSL_DESC,
          'value' => 'True',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_STRIPE_SCA_PROXY' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_PROXY_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_PROXY_DESC,
        ],
        'MODULE_PAYMENT_STRIPE_SCA_DEBUG_EMAIL' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_EMAIL_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_EMAIL_DESC
        ],
        'MODULE_PAYMENT_STRIPE_SCA_DAYS_DELETE' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_DAYS_DELETE_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_DAYS_DELETE_DESC,
          'value' => '2',
        ],
        'MODULE_PAYMENT_STRIPE_SCA_SORT_ORDER' => [
          'title' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_SORT_TITLE,
          'desc' => MODULE_PAYMENT_STRIPE_SCA_ADMIN_SOR_DESC,
          'value' => '0',
        ],
      ];

      return $params;
    }

    function sendTransactionToGateway($url, $parameters = null, $curl_opts = []) {
      $server = parse_url($url);

      if (isset($server['port']) === false) {
        $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
      }

      if (isset($server['path']) === false) {
        $server['path'] = '/';
      }

      $header = [
        'Stripe-Version: ' . $this->api_version,
        'User-Agent: Phoenix ' . Versions::get('Phoenix'),
      ];

      if (is_array($parameters) && !empty($parameters)) {
        $post_string = '';

        foreach ($parameters as $key => $value) {
          $post_string .= $key . '=' . urlencode(utf8_encode(trim($value))) . '&';
        }

        $post_string = substr($post_string, 0, -1);

        $parameters = $post_string;
      }

      $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
      curl_setopt($curl, CURLOPT_PORT, $server['port']);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
      curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
      curl_setopt($curl, CURLOPT_USERPWD, MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Live' ? MODULE_PAYMENT_STRIPE_SCA_LIVE_SECRET_KEY : MODULE_PAYMENT_STRIPE_SCA_TEST_SECRET_KEY . ':');
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

      if (!empty($parameters)) {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
      }

      if (MODULE_PAYMENT_STRIPE_SCA_VERIFY_SSL == 'True') {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        if (file_exists(DIR_FS_CATALOG . 'ext/modules/payment/stripe/data/ca-certificates.crt')) {
          curl_setopt($curl, CURLOPT_CAINFO, DIR_FS_CATALOG . 'ext/modules/payment/stripe/data/ca-certificates.crt');
        } elseif (file_exists(DIR_FS_CATALOG . 'includes/cacert.pem')) {
          curl_setopt($curl, CURLOPT_CAINFO, DIR_FS_CATALOG . 'includes/cacert.pem');
        }
      } else {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      }

      if (!Text::is_empty(MODULE_PAYMENT_STRIPE_SCA_PROXY)) {
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($curl, CURLOPT_PROXY, MODULE_PAYMENT_STRIPE_SCA_PROXY);
      }

      if (!empty($curl_opts)) {
        foreach ($curl_opts as $key => $value) {
          curl_setopt($curl, $key, $value);
        }
      }

      $result = curl_exec($curl);

      curl_close($curl);

      return $result;
    }

    function getTestLinkInfo() {
      $dialog_title = MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_TITLE;
      $dialog_button_close = MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_BUTTON_CLOSE;
      $dialog_success = MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_SUCCESS;
      $dialog_failed = MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_FAILED;
      $dialog_error = MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_ERROR;
      $dialog_connection_time = MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_TIME;

      if (defined('DIR_WS_ADMIN')) {
        $test_url = $GLOBALS['Admin']->link('modules.php', 'set=payment&module=' . $this->code . '&action=install&subaction=conntest');
      } else {
        $test_url = $GLOBALS['Linker']->build('modules.php', 'set=payment&module=' . $this->code . '&action=install&subaction=conntest');
      }

      if (MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Live') {
        $secret_key = MODULE_PAYMENT_STRIPE_SCA_LIVE_SECRET_KEY;
      } else {
        $secret_key = MODULE_PAYMENT_STRIPE_SCA_TEST_SECRET_KEY;
      }
      
      $js = <<<EOD
<script>
document.addEventListener('DOMContentLoaded', function() {
  var progressBar = document.getElementById('tcdprogressbar');
  if(progressBar){
    progressBar.style.width = '0%';
    progressBar.style.backgroundColor = '#4CAF50';
    progressBar.style.height = '20px';
    progressBar.style.transition = 'width 0.5s ease';
  }
});

function openTestConnectionDialog() {
  var dialogTemplate = document.getElementById('testConnectionDialog').innerHTML;
  var dialogContainer = document.createElement('div');
  dialogContainer.innerHTML = dialogTemplate;
  dialogContainer.style.padding = '20px';
  dialogContainer.style.border = '1px solid #ccc';
  dialogContainer.style.borderRadius = '5px';
  dialogContainer.style.backgroundColor = '#fff';
  dialogContainer.style.position = 'fixed';
  dialogContainer.style.top = '50%';
  dialogContainer.style.left = '50%';
  dialogContainer.style.transform = 'translate(-50%, -50%)';
  dialogContainer.style.zIndex = '1000';
  dialogContainer.style.boxShadow = '0px 0px 10px rgba(0,0,0,0.2)';

  var overlay = document.createElement('div');
  overlay.style.position = 'fixed';
  overlay.style.top = 0;
  overlay.style.left = 0;
  overlay.style.width = '100%';
  overlay.style.height = '100%';
  overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
  overlay.style.zIndex = '999';

  document.body.appendChild(overlay);
  document.body.appendChild(dialogContainer);

  var closeButton = document.createElement('button');
  closeButton.textContent = '{$dialog_button_close}';
  closeButton.style.marginTop = '20px';
  dialogContainer.appendChild(closeButton);
  closeButton.addEventListener('click', function() {
    document.body.removeChild(dialogContainer);
    document.body.removeChild(overlay);
  });

  var timeStart = new Date().getTime();
  var testUrl = 'https://api.stripe.com/v1/balance';
  
  fetch(testUrl, { 
    method: 'GET',
    headers: {
      "Authorization": "Bearer {$secret_key}", // Replace with your real key
      "Content-Type": "application/json"
    }
  })
  .then(response => {
    if (!response.ok) {
      throw new Error("HTTP error " + response.status);
    }
    return response.json();
  })
  .then(data => {
    var progressDiv = dialogContainer.querySelector('#testConnectionDialogProgress');
    progressDiv.innerHTML = '<p style="font-weight: bold; color: green;">{$dialog_success}</p>';
  })
  .catch(error => {
    var progressDiv = dialogContainer.querySelector('#testConnectionDialogProgress');
    progressDiv.innerHTML = '<p style="font-weight: bold; color: red;">{$dialog_failed}: ' + error.message + '</p>';
  })
  .finally(() => {
    var timeEnd = new Date().getTime();
    var timeTook = (timeEnd - timeStart) / 1000; // Tiempo en segundos con decimales

    var progressDiv = dialogContainer.querySelector('#testConnectionDialogProgress');
    progressDiv.innerHTML = progressDiv.innerHTML + '<p>{$dialog_connection_time} ' + timeTook.toFixed(3) + 's</p>';
  });
}
</script>
EOD;


      $info = '<p><i class="fas fa-lock"></i>&nbsp;<a href="javascript:openTestConnectionDialog();" style="text-decoration: underline; font-weight: bold;">' . MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_LINK_TITLE . '</a></p>' .
              '<div id="testConnectionDialog" style="display: none;"><p>Server:<br />https://api.stripe.com/v1/balance</p><div id="testConnectionDialogProgress"><p>' . MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_GENERAL_TEXT . '</p><div id="tcdprogressbar"></div></div></div>' .
              $js;
      return $info;
    }

    function getTestConnectionResult() {
      $stripe_result = json_decode($this->sendTransactionToGateway('https://api.stripe.com/v1/charges/phoenixcart_connection_test'), true);

      if (is_array($stripe_result) && !empty($stripe_result) && isset($stripe_result['error'])) {
        return 1;
      }

      return -1;
    }

    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency;

      if (empty($currency_code) || !$currencies->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(currencies::round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), 2, '', '');
    }

    function getSubmitCardDetailsMultilineJavascript($intent = null) {
      $stripe_publishable_key = MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Live' ? MODULE_PAYMENT_STRIPE_SCA_LIVE_PUBLISHABLE_KEY : MODULE_PAYMENT_STRIPE_SCA_TEST_PUBLISHABLE_KEY;
      $intent_url = $GLOBALS['Linker']->build("ext/modules/payment/stripe_sca/payment_intent.php", '', 'SSL', false, false);

      $js = <<<EOD
<style>
#stripe_table_new_card .card-details {
  background-color: #fff;
  padding: 10px 12px;
  border: 1px solid #ccc; opacity:0.6;
  border-radius: 6px;
}
#stripe_table_new_card .cardholder {
  padding: 0px;
}
</style>
EOD;
      
      $js .= <<<EOD
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  function show(element) {
    element.style.display = ''; // or 'block' depending on original styling
  }
  function hide(element) {
    element.style.display = 'none';
  }
  var checkoutConfirmationForm = document.querySelector('[name="checkout_confirmation"]');
  if (checkoutConfirmationForm) {
    checkoutConfirmationForm.id = 'payment-form';
  }
  var stripe = Stripe('{$stripe_publishable_key}');
  var elements = stripe.elements();
  var cardNumberElement = elements.create('cardNumber');
  var cardExpiryElement = elements.create('cardExpiry');
  var cardCvcElement = elements.create('cardCvc');
  cardNumberElement.mount('#card-number');
  cardExpiryElement.mount('#card-expiry');
  cardCvcElement.mount('#card-cvc');
  var paymentForm = document.getElementById('payment-form');

  if(paymentForm) {
    paymentForm.addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent default form submission
      var form = this;
      var submitButton = form.querySelector('button');
      if(submitButton)
        submitButton.disabled = true; // Disable submit button

      var selectedRadio = form.querySelector('input[name="stripe_card"]:checked');
      var selected = selectedRadio ? selectedRadio.value : undefined;
      var cardSaveCheckbox = form.querySelector('[name="card-save"]');
      var ccSave = cardSaveCheckbox ? cardSaveCheckbox.checked : false;
      if(typeof selected === 'undefined') {
        selected = 0;
      }

      try {
        if ((selected != null && selected != '0') || ccSave) {
          updatePaymentIntent(ccSave, selected);
        } else {
           processNewCardPayment();
        }
      } catch (error) {
        var paymentErrors = form.querySelector('.payment-errors');
          if(paymentErrors)
              paymentErrors.textContent = error;
          if(submitButton)
              submitButton.disabled = false;
      }
    });
  }
   var stripeTable = document.getElementById('stripe_table');
    if (stripeTable) {
      var closestTable = stripeTable.parentElement.closest('table');
       if (closestTable && !closestTable.getAttribute('width')) {
         closestTable.setAttribute('width', '100%');
       }
        var moduleRowsExtra = stripeTable.querySelectorAll('.moduleRowExtra');
          moduleRowsExtra.forEach(function(row){
            hide(row);
        });
        hide(document.getElementById('stripe_table_new_card'));
        var cardNumber = document.getElementById('card-number');
        if(cardNumber)
            cardNumber.id = 'new-card-number';

        var cardExpiry = document.getElementById('card-expiry');
        if(cardExpiry)
            cardExpiry.id = 'new-card-expiry';

        var cardCvc = document.getElementById('card-cvc');
        if(cardCvc)
            cardCvc.id = 'new-card-cvc';
        var saveCardNumber = document.getElementById('save-card-number');
        if(saveCardNumber)
            saveCardNumber.id = 'card-number';
        var saveCardExpiry = document.getElementById('save-card-expiry');
        if(saveCardExpiry)
            saveCardExpiry.id = 'card-expiry';
        var saveCardCvc = document.getElementById('save-card-cvc');
        if(saveCardCvc)
            saveCardCvc.id = 'card-cvc';

        var stripeRadioButtons = document.querySelectorAll('form[name="checkout_confirmation"] input[name="stripe_card"]');
        stripeRadioButtons.forEach(function(radioButton) {
         radioButton.addEventListener('change', function() {
          var selectedValue = this.value;
          if (selectedValue == '0') {
            stripeShowNewCardFields();
          } else {
            var stripeNewCardDiv = document.getElementById('stripe_table_new_card');
              if(stripeNewCardDiv && stripeNewCardDiv.style.display !== 'none')
              {
                var cardNumberNew = document.getElementById('new-card-number');
                if(cardNumberNew)
                    cardNumberNew.id = 'card-number';
                var cardExpiryNew = document.getElementById('new-card-expiry');
                if(cardExpiryNew)
                    cardExpiryNew.id = 'card-expiry';
                 var cardCvcNew = document.getElementById('new-card-cvc');
                if(cardCvcNew)
                   cardCvcNew.id = 'card-cvc';
                 var saveCardNumber = document.getElementById('save-card-number');
                 if(saveCardNumber)
                    saveCardNumber.id = 'card-number';
                var saveCardExpiry = document.getElementById('save-card-expiry');
                if(saveCardExpiry)
                   saveCardExpiry.id = 'card-expiry';
                var saveCardCvc = document.getElementById('save-card-cvc');
                if(saveCardCvc)
                   saveCardCvc.id = 'card-cvc';
              }

              var stripeNewCardDiv = document.getElementById('stripe_table_new_card');
              if(stripeNewCardDiv)
                hide(stripeNewCardDiv);

            }
            var tableRows = document.querySelectorAll('tr[id^="stripe_card_"]');
              tableRows.forEach(function(row){
                row.classList.remove('moduleRowSelected');
            });

           var selectedRow = document.getElementById('stripe_card_' + selectedValue);
           if(selectedRow)
              selectedRow.classList.add('moduleRowSelected');

        });

      });
    if(stripeRadioButtons.length > 0){
      stripeRadioButtons[0].checked = true;
      stripeRadioButtons[0].dispatchEvent(new Event('change'));
    }

    var moduleRows = stripeTable.querySelectorAll('.moduleRow');
    moduleRows.forEach(function(row) {
      row.addEventListener('mouseenter', function() {
        this.classList.add('moduleRowOver');
      });
      row.addEventListener('mouseleave', function() {
        this.classList.remove('moduleRowOver');
      });
      row.addEventListener('click', function(event) {
        var target = event.target;
          if(target.tagName != "INPUT" || target.type != "radio")
          {
            this.querySelectorAll('input:radio').forEach(function(radioButton){
              if(radioButton.checked == false)
              {
                 radioButton.checked = true;
                 radioButton.dispatchEvent(new Event('change'));
              }
            })
          }
      });
    });
  } else {
      var stripeTableNewCard = document.getElementById('stripe_table_new_card');
      if(stripeTableNewCard) {
        var closestTableNewCard = stripeTableNewCard.parentElement.closest('table');
        if(closestTableNewCard && !closestTableNewCard.getAttribute('width')) {
            closestTableNewCard.setAttribute('width', '100%');
        }
      }
  }

  function stripeShowNewCardFields() {

    var saveCardNumber = document.getElementById('card-number');
    if(saveCardNumber)
      saveCardNumber.id = 'save-card-number';
    var saveCardExpiry = document.getElementById('card-expiry');
    if(saveCardExpiry)
      saveCardExpiry.id = 'save-card-expiry';
    var saveCardCvc = document.getElementById('card-cvc');
    if(saveCardCvc)
     saveCardCvc.id = 'save-card-cvc';
    var cardNumber = document.getElementById('new-card-number');
    if(cardNumber)
      cardNumber.id = 'card-number';
    var cardExpiry = document.getElementById('new-card-expiry');
    if(cardExpiry)
      cardExpiry.id = 'card-expiry';
    var cardCvc = document.getElementById('new-card-cvc');
    if(cardCvc)
      cardCvc.id = 'card-cvc';
    var stripeTableNewCard = document.getElementById('stripe_table_new_card');
    if(stripeTableNewCard)
      show(stripeTableNewCard);

  }

    function updatePaymentIntent(cc_save, token) {

      var intentId = document.getElementById('intent_id').value;
      var customerId = document.getElementById('customer_id').value;
      var intentUrl = "{$intent_url}";

      fetch(intentUrl + '?id=' + intentId + '&token=' + token + '&customer_id=' + customerId + '&cc_save=' + cc_save, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json'
         }
      })
      .then(response => response.json())
      .then(data => {

        if (data.status == 'ok') {
          var selectedRadio = document.querySelector('input[name="stripe_card"]:checked');
          var selected = selectedRadio ? selectedRadio.value : undefined;

          if (selected == null || selected == '0') {
              processNewCardPayment();
          } else {
              processSavedCardPayment(data.payment_method);
          }
        } else {
          var form = document.getElementById('payment-form');
          var submitButton = form.querySelector('button');
          if(submitButton)
              submitButton.disabled = false;
           var cardErrors = document.getElementById('card-errors');
          if(cardErrors)
            cardErrors.textContent = data.error;
        }
      })
       .catch(error => {
          console.error('Error fetching intent:', error);
          var form = document.getElementById('payment-form');
            var submitButton = form.querySelector('button');
            if(submitButton)
                submitButton.disabled = false;
          var cardErrors = document.getElementById('card-errors');
            if(cardErrors)
              cardErrors.textContent = "An error occurred while processing the request.";

      });
  }

  function processNewCardPayment() {
  var cardholderName = document.getElementById('cardholder-name').value;
  var addressCity = document.getElementById('address_city').value;
  var addressLine1 = document.getElementById('address_line1').value;
  var addressZip = document.getElementById('address_zip').value;
  var addressState = document.getElementById('address_state').value;
  var addressCountry = document.getElementById('address_country').value;
  var emailAddress = document.getElementById('email_address').value;
  var secret = document.getElementById('secret').value;
    stripe.handleCardPayment(
      secret, cardNumberElement, {
        payment_method_data: {
          billing_details: {
            name: cardholderName,
            address: {
              city: addressCity,
              line1: addressLine1,
              postal_code: addressZip,
              state: addressState,
              country: addressCountry
            },
            email: emailAddress
          }
        }
      }
    ).then(function(result) {
        stripeResponseHandler(result);
    });
  }

  function processSavedCardPayment(payment_method_id) {
    var secret = document.getElementById('secret').value;
    stripe.handleCardPayment(
      secret,
      {
        payment_method: payment_method_id
      }
    ).then(function(result) {
      stripeResponseHandler(result);
    });
  }

  function stripeResponseHandler(result) {
    var form = document.getElementById('payment-form');
    var cardErrors = document.getElementById('card-errors');
     var submitButton = form.querySelector('button');

    if (result.error) {
       if(cardErrors)
         cardErrors.textContent = result.error.message;
        if(submitButton)
         submitButton.disabled = false;
    } else {
      if(cardErrors)
          cardErrors.textContent = 'Processing';
      var hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'stripeIntentId';
      hiddenInput.value = result.paymentIntent.id;
      form.appendChild(hiddenInput);

      form.submit();
    }
  }
});
</script>
EOD;

      return $js;
    }

    function getSubmitCardDetailsOnelineJavascript($intent = null) {
      $stripe_publishable_key = MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Live' ? MODULE_PAYMENT_STRIPE_SCA_LIVE_PUBLISHABLE_KEY : MODULE_PAYMENT_STRIPE_SCA_TEST_PUBLISHABLE_KEY;
      $intent_url = $GLOBALS['Linker']->build("ext/modules/payment/stripe_sca/payment_intent.php", '', 'SSL', false, false);

      $js = <<<EOD
<style>
#stripe_table_new_card #card-element {
  background-color: #fff;
  padding: 10px 2px;
  border: 1px solid #ccc; opacity:0.6;
  border-radius: 6px;
}
</style>
EOD;


      $js .= <<<EOD
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  function show(element) {
    element.style.display = ''; // or 'block' depending on original styling
  }
  function hide(element) {
    element.style.display = 'none';
  }
  var checkoutConfirmationForm = document.querySelector('[name="checkout_confirmation"]');
  if (checkoutConfirmationForm) {
    checkoutConfirmationForm.id = 'payment-form';
  }
  var stripe = Stripe('{$stripe_publishable_key}');
  var elements = stripe.elements();
  var card = elements.create('card', {hidePostalCode: true});
  card.mount('#card-element');
  var paymentForm = document.getElementById('payment-form');
  if(paymentForm)
   {
    paymentForm.addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent default form submission

      var form = this;
      var submitButton = form.querySelector('button');
      if(submitButton)
         submitButton.disabled = true; // Disable submit button

      var selectedRadio = form.querySelector('input[name="stripe_card"]:checked');
      var selected = selectedRadio ? selectedRadio.value : undefined;
      var cardSaveCheckbox = form.querySelector('[name="card-save"]');
      var ccSave = cardSaveCheckbox ? cardSaveCheckbox.checked : false;

      if(typeof selected === 'undefined') {
          selected = 0;
      }
      try {
        if ((selected != null && selected != '0') || ccSave) {
          updatePaymentIntent(ccSave, selected);
        } else {
          processNewCardPayment();
        }
      } catch (error) {
        var paymentErrors = form.querySelector('.payment-errors');
        if(paymentErrors)
          paymentErrors.textContent = error;
        if(submitButton)
          submitButton.disabled = false;
      }
    });
  }

 var stripeTable = document.getElementById('stripe_table');
  if (stripeTable) {
    var closestTable = stripeTable.parentElement.closest('table');
     if (closestTable && !closestTable.getAttribute('width')) {
       closestTable.setAttribute('width', '100%');
     }
      var moduleRowsExtra = stripeTable.querySelectorAll('.moduleRowExtra');
        moduleRowsExtra.forEach(function(row){
          hide(row);
      });
      hide(document.getElementById('stripe_table_new_card'));
      var cardElement = document.getElementById('card-element');
      if(cardElement)
        cardElement.id = 'new-card-element';

      var saveCardElement = document.getElementById('save-card-element');
      if(saveCardElement)
        saveCardElement.id = 'card-element';

      var stripeRadioButtons = document.querySelectorAll('form[name="checkout_confirmation"] input[name="stripe_card"]');
      stripeRadioButtons.forEach(function(radioButton) {
        radioButton.addEventListener('change', function() {
        var selectedValue = this.value;
          if (selectedValue == '0') {
              stripeShowNewCardFields();
          } else {
            var stripeNewCardDiv = document.getElementById('stripe_table_new_card');
            if(stripeNewCardDiv && stripeNewCardDiv.style.display !== 'none')
              {
                var cardElementNew = document.getElementById('new-card-element');
                if(cardElementNew)
                  cardElementNew.id = 'card-element';
                var saveCardElement = document.getElementById('save-card-element');
                if(saveCardElement)
                  saveCardElement.id = 'card-element';
              }
           var stripeNewCardDiv = document.getElementById('stripe_table_new_card');
              if(stripeNewCardDiv)
                hide(stripeNewCardDiv);
          }
          var tableRows = document.querySelectorAll('tr[id^="stripe_card_"]');
            tableRows.forEach(function(row){
              row.classList.remove('moduleRowSelected');
          });

          var selectedRow = document.getElementById('stripe_card_' + selectedValue);
          if(selectedRow)
            selectedRow.classList.add('moduleRowSelected');
        });
    });
    if(stripeRadioButtons.length > 0){
      stripeRadioButtons[0].checked = true;
      stripeRadioButtons[0].dispatchEvent(new Event('change'));
    }

    var moduleRows = stripeTable.querySelectorAll('.moduleRow');
    moduleRows.forEach(function(row) {
      row.addEventListener('mouseenter', function() {
        this.classList.add('moduleRowOver');
      });
      row.addEventListener('mouseleave', function() {
        this.classList.remove('moduleRowOver');
      });
     row.addEventListener('click', function(event) {
        var target = event.target;
        if(target.tagName != "INPUT" || target.type != "radio")
        {
          this.querySelectorAll('input:radio').forEach(function(radioButton){
            if(radioButton.checked == false)
            {
               radioButton.checked = true;
               radioButton.dispatchEvent(new Event('change'));
            }
          })
        }
      });
    });
  } else {
     var stripeTableNewCard = document.getElementById('stripe_table_new_card');
     if(stripeTableNewCard) {
      var closestTableNewCard = stripeTableNewCard.parentElement.closest('table');
      if(closestTableNewCard && !closestTableNewCard.getAttribute('width')) {
        closestTableNewCard.setAttribute('width', '100%');
      }
    }
  }
  function stripeShowNewCardFields() {
    var cardElement = document.getElementById('card-element');
     if(cardElement)
        cardElement.id = 'save-card-element';
     var newCardElement = document.getElementById('new-card-element');
     if(newCardElement)
        newCardElement.id = 'card-element';
     var stripeTableNewCard = document.getElementById('stripe_table_new_card');
      if(stripeTableNewCard)
        show(stripeTableNewCard);
  }
  function updatePaymentIntent(cc_save, token) {
    var intentId = document.getElementById('intent_id').value;
    var customerId = document.getElementById('customer_id').value;
    var intentUrl = "{$intent_url}";

    fetch(intentUrl + '?id=' + intentId + '&token=' + token + '&customer_id=' + customerId + '&cc_save=' + cc_save, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
       }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status == 'ok') {
          var selectedRadio = document.querySelector('input[name="stripe_card"]:checked');
          var selected = selectedRadio ? selectedRadio.value : undefined;
          if (selected == null || selected == '0') {
            processNewCardPayment();
          } else {
            processSavedCardPayment(data.payment_method);
          }
      } else {
        var form = document.getElementById('payment-form');
        var submitButton = form.querySelector('button');
          if(submitButton)
            submitButton.disabled = false;
          var cardErrors = document.getElementById('card-errors');
          if(cardErrors)
            cardErrors.textContent = data.error;

      }
    })
    .catch(error => {
      console.error('Error fetching intent:', error);
      var form = document.getElementById('payment-form');
         var submitButton = form.querySelector('button');
        if(submitButton)
             submitButton.disabled = false;
      var cardErrors = document.getElementById('card-errors');
        if(cardErrors)
          cardErrors.textContent = "An error occurred while processing the request.";
    });
  }

  function processNewCardPayment() {
    var cardholderName = document.getElementById('cardholder-name').value;
    var addressCity = document.getElementById('address_city').value;
    var addressLine1 = document.getElementById('address_line1').value;
    var addressZip = document.getElementById('address_zip').value;
    var addressState = document.getElementById('address_state').value;
    var addressCountry = document.getElementById('address_country').value;
    var emailAddress = document.getElementById('email_address').value;
    var secret = document.getElementById('secret').value;
    stripe.handleCardPayment(
      secret, card, {
        payment_method_data: {
          billing_details: {
            name: cardholderName,
            address: {
              city: addressCity,
              line1: addressLine1,
              postal_code: addressZip,
              state: addressState,
              country: addressCountry
            },
            email: emailAddress
          }
        }
      }
      ).then(function(result) {
        stripeResponseHandler(result);
      });
  }
  function processSavedCardPayment(payment_method_id) {
    var secret = document.getElementById('secret').value;
    stripe.handleCardPayment(
      secret,
      {
        payment_method: payment_method_id
      }
    ).then(function(result) {
      stripeResponseHandler(result);
    });
  }

  function stripeResponseHandler(result) {
    var form = document.getElementById('payment-form');
    var cardErrors = document.getElementById('card-errors');
    var submitButton = form.querySelector('button');

    if (result.error) {
      if(cardErrors)
        cardErrors.textContent = result.error.message;
        if(submitButton)
          submitButton.disabled = false;
    } else {
      if(cardErrors)
        cardErrors.textContent = 'Processing';
      var hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'stripeIntentId';
      hiddenInput.value = result.paymentIntent.id;
      form.appendChild(hiddenInput);
      form.submit();
    }
  }
});
</script>
EOD;

      return $js;
    }

    function sendDebugEmail($response = []) {
      if (!Text::is_empty(MODULE_PAYMENT_STRIPE_SCA_DEBUG_EMAIL)) {
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
          Notifications::mail('', MODULE_PAYMENT_STRIPE_SCA_DEBUG_EMAIL, 'Stripe Debug E-Mail', trim($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
      }
    }

    function deleteCard($card, $customer, $token_id) {
      global $db;

      $secret_key = MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Live' ? MODULE_PAYMENT_STRIPE_SCA_LIVE_SECRET_KEY : MODULE_PAYMENT_STRIPE_SCA_TEST_SECRET_KEY;
      \Stripe\Stripe::setApiKey($secret_key);
      \Stripe\Stripe::setApiVersion($this->api_version);
      $error = '';
      $payment_method = \Stripe\PaymentMethod::retrieve($card);
      try {
        $result = $payment_method->detach();
      } catch (exception $err) {
        // just log error, and continue to delete card from table
        $error = $err->getMessage();
      }

      $this->event_log($_SESSION['customer_id'], "deleteCard", $payment_method, $error);

      if (!isset($result->object) || ($result->object !== 'payment_method')) {
        $this->sendDebugEmail($result . PHP_EOL . $error);
      }

      $db->query(sprintf(<<<'EOSQL'
DELETE
  FROM customers_stripe_tokens
  WHERE id = %s
    AND customers_id = %s
    AND stripe_token = '%s'
EOSQL
      , (int)$token_id, (int)$_SESSION['customer_id'], $db->escape(Text::prepare($customer . ':|:' . $card))));

      return (mysqli_affected_rows($db) === 1);

   }

  }
