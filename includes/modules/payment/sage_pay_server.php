<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class sage_pay_server extends abstract_payment_module {

    const CONFIG_KEY_BASE = 'MODULE_PAYMENT_SAGE_PAY_SERVER_';

    const REQUIRES = [
      'firstname',
      'lastname',
      'street_address',
      'city',
      'postcode',
      'country',
      'telephone',
      'email_address',
    ];

    public function __construct() {
      $this->signature = 'sage_pay|sage_pay_server|2.0|2.3';
      $this->api_version = '3.00';

      parent::__construct();
      $this->public_title = MODULE_PAYMENT_SAGE_PAY_SERVER_TEXT_PUBLIC_TITLE;
      $this->sort_order = $this->sort_order ?? 0;
      $this->order_status = defined('MODULE_PAYMENT_SAGE_PAY_SERVER_ORDER_STATUS_ID') && ((int)MODULE_PAYMENT_SAGE_PAY_SERVER_ORDER_STATUS_ID > 0) ? (int)MODULE_PAYMENT_SAGE_PAY_SERVER_ORDER_STATUS_ID : 0;

      if ( defined('MODULE_PAYMENT_SAGE_PAY_SERVER_STATUS') ) {
        if ( MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_SERVER == 'Test' ) {
          $this->title .= ' [Test]';
          $this->public_title .= ' (' . $this->code . '; Test)';
        }

        $this->description .= $this->getTestLinkInfo();
      }

      if ( ('modules.php' === Request::get_page()) && ('install' === ($_GET['action'] ?? null)) && ('conntest' === ($_GET['subaction'] ?? null)) ) {
        echo $this->getTestConnectionResult();
        exit();
      }

      if ($this->enabled !== true) {
        return;
      }

      if ( Text::is_empty(MODULE_PAYMENT_SAGE_PAY_SERVER_VENDOR_LOGIN_NAME) ) {
        $this->description = '<div class="alert alert-warning">' . MODULE_PAYMENT_SAGE_PAY_SERVER_ERROR_ADMIN_CONFIGURATION . '</div>' . $this->description;

        $this->enabled = false;
      }

      if ( !function_exists('curl_init') ) {
        $this->description = '<div class="alert alert-warning">' . MODULE_PAYMENT_SAGE_PAY_SERVER_ERROR_ADMIN_CURL . '</div>' . $this->description;

        $this->enabled = false;
      }
    }

    public function before_process() {
      global $sagepay_server_transaction_details, $order, $customer_data, $currencies;

      $sagepay_server_transaction_details = null;

      $error = null;

      if (isset($_GET['check']) && ($_GET['check'] == 'PROCESS')) {
        if ( isset($_GET['skcode']) && isset($_SESSION['sagepay_server_skey_code']) && ($_GET['skcode'] == $_SESSION['sagepay_server_skey_code']) ) {
          $skcode = Text::input($_GET['skcode']);

          $sp_query = $GLOBALS['db']->query("SELECT verified, transaction_details FROM sagepay_server_securitykeys WHERE code = '" . $GLOBALS['db']->escape($skcode) . "' LIMIT 1");

          if ( $sp = $sp_query->fetch_assoc() ) {
            unset($_SESSION['sagepay_server_skey_code']);
            $GLOBALS['db']->query("DELETE FROM sagepay_server_securitykeys WHERE code = '" . $GLOBALS['db']->escape($skcode) . "'");

            if ( $sp['verified'] == '1' ) {
              $sagepay_server_transaction_details = $sp['transaction_details'];

              return true;
            }
          }
        }
      } else {
        if ( !isset($_SESSION['sagepay_server_skey_code']) ) {
          $_SESSION['sagepay_server_skey_code'] = Password::create_random(16);
        }

        $params = [
          'VPSProtocol' => $this->api_version,
          'ReferrerID' => 'C74D7B82-E9EB-4FBD-93DB-76F0F551C802',
          'Vendor' => substr(MODULE_PAYMENT_SAGE_PAY_SERVER_VENDOR_LOGIN_NAME, 0, 15),
          'VendorTxCode' => substr(date('YmdHis') . '-' . $_SESSION['customer_id'] . '-' . $_SESSION['cartID'], 0, 40),
          'Amount' => $currencies->format_raw($order->info['total']),
          'Currency' => $_SESSION['currency'],
          'Description' => substr(STORE_NAME, 0, 100),
          'NotificationURL' => $this->formatURL($GLOBALS['Linker']->build('ext/modules/payment/sage_pay/server.php', ['check' => 'SERVER', 'skcode' => $_SESSION['sagepay_server_skey_code']], false)),
          'BillingSurname' => substr($customer_data->get('lastname', $order->billing), 0, 20),
          'BillingFirstnames' => substr($customer_data->get('firstname', $order->billing), 0, 20),
          'BillingAddress1' => substr($customer_data->get('street_address', $order->billing), 0, 100),
          'BillingCity' => substr($customer_data->get('city', $order->billing), 0, 40),
          'BillingPostCode' => substr($customer_data->get('postcode', $order->billing), 0, 10),
          'BillingCountry' => $customer_data->get('country_iso_code_2', $order->billing),
          'BillingPhone' => substr($customer_data->get('telephone', $order->customer), 0, 20),
          'DeliverySurname' => substr($customer_data->get('lastname', $order->delivery), 0, 20),
          'DeliveryFirstnames' => substr($customer_data->get('firstname', $order->delivery), 0, 20),
          'DeliveryAddress1' => substr($customer_data->get('street_address', $order->delivery), 0, 100),
          'DeliveryCity' => substr($customer_data->get('city', $order->delivery), 0, 40),
          'DeliveryPostCode' => substr($customer_data->get('postcode', $order->delivery), 0, 10),
          'DeliveryCountry' => $customer_data->get('country_iso_code_2', $order->delivery),
          'DeliveryPhone' => substr($customer_data->get('telephone', $order->customer), 0, 20),
          'CustomerEMail' => substr($customer_data->get('email_address', $order->customer), 0, 255),
          'Apply3DSecure' => '0',
        ];

        $ip_address = Request::get_ip();

        if ( (ip2long($ip_address) != -1) && (ip2long($ip_address) != false) ) {
          $params['ClientIPAddress']= $ip_address;
        }

        if ( MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_METHOD == 'Payment' ) {
          $params['TxType'] = 'PAYMENT';
        } elseif ( MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_METHOD == 'Deferred' ) {
          $params['TxType'] = 'DEFERRED';
        } else {
          $params['TxType'] = 'AUTHENTICATE';
        }

        if ($params['BillingCountry'] === 'US') {
          $params['BillingState'] = Zone::fetch_code($customer_data->get('country_id', $order->billing), $customer_data->get('zone_id', $order->billing), '');
        }

        if ($params['DeliveryCountry'] === 'US') {
          $params['DeliveryState'] = Zone::fetch_code($customer_data->get('country_id', $order->delivery), $customer_data->get('zone_id', $order->delivery), '');
        }

        if ( MODULE_PAYMENT_SAGE_PAY_SERVER_PROFILE_PAGE !== 'Normal' ) {
          $params['Profile'] = 'LOW';
        }

        $contents = [];

        foreach ($order->products as $product) {
          $product_name = $product['name'];

          foreach (($product['attributes'] ?? []) as $att) {
            $product_name .= '; ' . $att['option'] . '=' . $att['value'];
          }

          $tax = ($product['tax'] / 100) * $product['final_price'];
          $contents[] = str_replace(
            [':', "\n", "\r", '&'],
            '',
            $product_name) . ':' . $product['qty']
          . ':' . $currencies->format_raw($product['final_price'])
          . ':' . $currencies->format_raw($tax)
          . ':' . $currencies->format_raw($tax + $product['final_price'])
          . ':' . $currencies->format_raw(($tax + $product['final_price']) * $product['qty']);
        }

        foreach ($order->totals as $ot) {
          $contents[] = str_replace([':', "\n", "\r", '&'], '', strip_tags($ot['title'])) . ':---:---:---:---:' . $currencies->format_raw($ot['value']);
        }

        $params['Basket'] = substr(count($contents) . ':' . implode(':', $contents), 0, 7500);

        $post_string = '';

        foreach ($params as $key => $value) {
          $post_string .= $key . '=' . urlencode(trim($value)) . '&';
        }

        if ( MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_SERVER == 'Live' ) {
          $gateway_url = 'https://live.sagepay.com/gateway/service/vspserver-register.vsp';
        } else {
          $gateway_url = 'https://test.sagepay.com/gateway/service/vspserver-register.vsp';
        }

        $transaction_response = $this->sendTransactionToGateway($gateway_url, $post_string);

        $string_array = explode(chr(10), $transaction_response);
        $return = [];

        foreach ($string_array as $string) {
          if (strpos($string, '=') != false) {
            $parts = explode('=', $string, 2);
            $return[trim($parts[0])] = trim($parts[1]);
          }
        }

        if ($return['Status'] == 'OK') {
          $sp_query = $GLOBALS['db']->query('SELECT id, securitykey FROM sagepay_server_securitykeys WHERE code = "' . $GLOBALS['db']->escape($_SESSION['sagepay_server_skey_code']) . '" LIMIT 1');

          if ( $sp = $sp_query->fetch_assoc() ) {
            if ( $sp['securitykey'] != $return['SecurityKey'] ) {
              $GLOBALS['db']->query('UPDATE sagepay_server_securitykeys SET securitykey = "' . $GLOBALS['db']->escape($return['SecurityKey']) . '", date_added = NOW() WHERE id = "' . (int)$sp['id'] . '"');
            }
          } else {
            $GLOBALS['db']->query('INSERT INTO sagepay_server_securitykeys (code, securitykey, date_added) VALUES ("' . $GLOBALS['db']->escape($_SESSION['sagepay_server_skey_code']) . '", "' . $GLOBALS['db']->escape($return['SecurityKey']) . '", NOW())');
          }

          if ( MODULE_PAYMENT_SAGE_PAY_SERVER_PROFILE_PAGE == 'Normal' ) {
            Href::redirect($return['NextURL']);
          } else {
            $_SESSION['sage_pay_server_nexturl'] = $return['NextURL'];

            Href::redirect($GLOBALS['Linker']->build('ext/modules/payment/sage_pay/checkout.php'));
          }
        } else {
          $error = $this->getErrorMessageNumber($return['StatusDetail']);

          $this->sendDebugEmail($return);
        }
      }

      $link = $GLOBALS['Linker']->build('checkout_payment.php', ['payment_error' => $this->code]);
      if (!Text::is_empty($error)) {
        $link->set_parameter('error', $error);
      }

      Href::redirect($link);
    }

    public function after_process() {
      global $order_id, $sagepay_server_transaction_details;

      $sql_data = [
        'orders_id' => $order_id,
        'orders_status_id' => DEFAULT_ORDERS_STATUS_ID,
        'date_added' => 'NOW()',
        'customer_notified' => '0',
        'comments' => trim($sagepay_server_transaction_details),
      ];

      $GLOBALS['db']->perform('orders_status_history', $sql_data);

      if ( MODULE_PAYMENT_SAGE_PAY_SERVER_PROFILE_PAGE == 'Low' ) {
        require 'includes/system/segments/checkout/reset.php';

        unset($_SESSION['sage_pay_server_nexturl']);

        Href::redirect($GLOBALS['Linker']->build('ext/modules/payment/sage_pay/redirect.php'));
      }
    }

    public function get_error() {
      $message = MODULE_PAYMENT_SAGE_PAY_SERVER_ERROR_GENERAL;

      $error_number = null;

      if ( isset($_GET['error']) && is_numeric($_GET['error']) && $this->errorMessageNumberExists($_GET['error']) ) {
        $error_number = $_GET['error'];
      }

      if ( isset($error_number) ) {
// don't show an error message for user cancelled/aborted transactions
        if ( $error_number == '2013' ) {
          return false;
        }

        $message = $this->getErrorMessage($error_number) . ' ' . MODULE_PAYMENT_SAGE_PAY_SERVER_ERROR_GENERAL;
      }

      $error = [
        'title' => MODULE_PAYMENT_SAGE_PAY_SERVER_ERROR_TITLE,
        'error' => $message,
      ];

      return $error;
    }

    protected function get_parameters() {
      if ( mysqli_num_rows($GLOBALS['db']->query("SHOW TABLES LIKE 'sagepay_server_securitykeys'")) != 1 ) {
        $sql = <<<EOD
CREATE TABLE sagepay_server_securitykeys (
  id int NOT NULL auto_increment,
  code char(16) NOT NULL,
  securitykey char(10) NOT NULL,
  date_added datetime NOT NULL,
  verified char(1) DEFAULT 0,
  transaction_details text,
  PRIMARY KEY (id),
  KEY idx_sagepay_server_securitykeys_code (code),
  KEY idx_sagepay_server_securitykeys_securitykey (securitykey)
);
EOD;

        $GLOBALS['db']->query($sql);
      }

      $params = [
        'MODULE_PAYMENT_SAGE_PAY_SERVER_STATUS' => [
          'title' => 'Enable Sage Pay Server Module',
          'desc' => 'Do you want to accept Sage Pay Server payments?',
          'value' => 'True',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_VENDOR_LOGIN_NAME' => [
          'title' => 'Vendor Login Name',
          'desc' => 'The vendor login name to connect to the gateway with.',
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_PROFILE_PAGE' => [
          'title' => 'Profile Payment Page',
          'desc' => 'Profile page to use for the payment page, Normal is a full redirect to Sage Pay and Low loads through an iframe.',
          'value' => 'Normal',
          'set_func' => "Config::select_one(['Normal', 'Low'], ",
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_METHOD' => [
          'title' => 'Transaction Method',
          'desc' => 'The processing method to use for each transaction.',
          'value' => 'Authenticate',
          'set_func' => "Config::select_one(['Authenticate', 'Deferred', 'Payment'], ",
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_ORDER_STATUS_ID' => [
          'title' => 'Set Order Status',
          'desc' => 'Set the status of orders made with this payment module to this value',
          'value' => '0',
          'use_func' => 'order_status::fetch_name',
          'set_func' => 'Config::select_order_status(',
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_ORDER_STATUS_ID' => [
          'title' => 'Transaction Order Status',
          'desc' => 'Include transaction information in this order status level',
          'value' => self::ensure_order_status('MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_ORDER_STATUS_ID', 'Sage Pay [Transactions]'),
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_ZONE' => [
          'title' => 'Payment Zone',
          'desc' => 'If a zone is selected, only enable this payment method for that zone.',
          'value' => '0',
          'use_func' => 'geo_zone::fetch_name',
          'set_func' => 'Config::select_geo_zone(',
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_SERVER' => [
          'title' => 'Transaction Server',
          'desc' => 'Perform transactions on the production server or on the testing server.',
          'value' => 'Live',
          'set_func' => "Config::select_one(['Live', 'Test'], ",
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_VERIFY_SSL' => [
          'title' => 'Verify SSL Certificate',
          'desc' => 'Verify transaction server SSL certificate on connection?',
          'value' => 'True',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_PROXY' => [
          'title' => 'Proxy Server',
          'desc' => 'Send API requests through this proxy server. (host:port, eg: 123.45.67.89:8080 or proxy.example.com:8080)',
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_DEBUG_EMAIL' => [
          'title' => 'Debug E-Mail Address',
          'desc' => 'All parameters of an invalid transaction will be sent to this email address.',
        ],
        'MODULE_PAYMENT_SAGE_PAY_SERVER_SORT_ORDER' => [
          'title' => 'Sort order of display.',
          'desc' => 'Sort order of display. Lowest is displayed first.',
          'value' => '0',
        ],
      ];

      return $params;
    }

    public function sendTransactionToGateway($url, $parameters) {
      $server = parse_url($url);

      if (isset($server['port']) === false) {
        $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
      }

      if (isset($server['path']) === false) {
        $server['path'] = '/';
      }

      $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
      curl_setopt($curl, CURLOPT_PORT, $server['port']);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
      curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);

      if ( MODULE_PAYMENT_SAGE_PAY_SERVER_VERIFY_SSL == 'True' ) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        if ( file_exists(DIR_FS_CATALOG . 'ext/modules/payment/sage_pay/sagepay.com.crt') ) {
          curl_setopt($curl, CURLOPT_CAINFO, DIR_FS_CATALOG . 'ext/modules/payment/sage_pay/sagepay.com.crt');
        } elseif ( file_exists(DIR_FS_CATALOG . 'includes/cacert.pem') ) {
          curl_setopt($curl, CURLOPT_CAINFO, DIR_FS_CATALOG . 'includes/cacert.pem');
        }
      } else {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      }

      if ( !Text::is_empty(MODULE_PAYMENT_SAGE_PAY_SERVER_PROXY) ) {
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($curl, CURLOPT_PROXY, MODULE_PAYMENT_SAGE_PAY_SERVER_PROXY);
      }

      $result = curl_exec($curl);

      curl_close($curl);

      return $result;
    }

// format prices without currency formatting
    function format_raw($number, $currency_code = '', $currency_value = '') {
      return $GLOBALS['currencies']->format_raw($number, true, $currency_code, $currency_value);
    }

    public function loadErrorMessages() {
      $errors = [];

      $path = DIR_FS_CATALOG . '/ext/modules/payment/sage_pay/errors.php';
      if (file_exists($path)) {
        include $path;
      }

      $this->_error_messages = $errors;
    }

    public function getErrorMessageNumber($string) {
      if (!isset($this->_error_messages)) {
        $this->loadErrorMessages();
      }

      $error = explode(' ', $string, 2);

      if (is_numeric($error[0]) && $this->errorMessageNumberExists($error[0])) {
        return $error[0];
      }

      return false;
    }

    public function getErrorMessage($number) {
      if (!isset($this->_error_messages)) {
        $this->loadErrorMessages();
      }

      if (is_numeric($number) && $this->errorMessageNumberExists($number)) {
        return $this->_error_messages[$number];
      }

      return false;
    }

    public function errorMessageNumberExists($number) {
      if (!isset($this->_error_messages)) {
        $this->loadErrorMessages();
      }

      return (is_numeric($number) && isset($this->_error_messages[$number]));
    }

    public function formatURL($url) {
      return str_replace('&amp;', '&', $url);
    }

    public function getTestLinkInfo() {
      $dialog_title = MODULE_PAYMENT_SAGE_PAY_SERVER_DIALOG_CONNECTION_TITLE;
      $dialog_button_close = MODULE_PAYMENT_SAGE_PAY_SERVER_DIALOG_CONNECTION_BUTTON_CLOSE;
      $dialog_success = MODULE_PAYMENT_SAGE_PAY_SERVER_DIALOG_CONNECTION_SUCCESS;
      $dialog_failed = MODULE_PAYMENT_SAGE_PAY_SERVER_DIALOG_CONNECTION_FAILED;
      $dialog_error = MODULE_PAYMENT_SAGE_PAY_SERVER_DIALOG_CONNECTION_ERROR;
      $dialog_connection_time = MODULE_PAYMENT_SAGE_PAY_SERVER_DIALOG_CONNECTION_TIME;

      $test_url = $GLOBALS['Linker']->build('modules.php', ['set' => 'payment', 'module' => $this->code, 'action' => 'install', 'subaction' => 'conntest']);

      $js = <<<"EOD"
<script>
$(function() {
  $('#tcdprogressbar').progressbar({
    value: false
  });
});

function openTestConnectionDialog() {
  var d = $('<div>').html($('#testConnectionDialog').html()).dialog({
    modal: true,
    title: '{$dialog_title}',
    buttons: {
      '{$dialog_button_close}': function () {
        $(this).dialog('destroy');
      }
    }
  });

  var timeStart = new Date().getTime();

  $.ajax({
    url: '{$test_url}'
  }).done(function(data) {
    if ( data == '1' ) {
      d.find('#testConnectionDialogProgress').html('<p style="font-weight: bold; color: green;">{$dialog_success}</p>');
    } else {
      d.find('#testConnectionDialogProgress').html('<p style="font-weight: bold; color: red;">{$dialog_failed}</p>');
    }
  }).fail(function() {
    d.find('#testConnectionDialogProgress').html('<p style="font-weight: bold; color: red;">{$dialog_error}</p>');
  }).always(function() {
    var timeEnd = new Date().getTime();
    var timeTook = new Date(0, 0, 0, 0, 0, 0, timeEnd-timeStart);

    d.find('#testConnectionDialogProgress').append('<p>{$dialog_connection_time} ' + timeTook.getSeconds() + '.' + timeTook.getMilliseconds() + 's</p>');
  });
}
</script>
EOD;

      $info = '<p><i class="fas fa-lock"></i>&nbsp;<a href="javascript:openTestConnectionDialog();" style="text-decoration: underline; font-weight: bold;">' . MODULE_PAYMENT_SAGE_PAY_SERVER_DIALOG_CONNECTION_LINK_TITLE . '</a></p>' .
              '<div id="testConnectionDialog" style="display: none;"><p>';

      if ( MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_SERVER == 'Live' ) {
        $info .= 'Live Server:<br>https://live.sagepay.com/gateway/service/vspserver-register.vsp';
      } else {
        $info .= 'Test Server:<br>https://test.sagepay.com/gateway/service/vspserver-register.vsp';
      }

      $info .= '</p><div id="testConnectionDialogProgress"><p>' . MODULE_PAYMENT_SAGE_PAY_SERVER_DIALOG_CONNECTION_GENERAL_TEXT . '</p><div id="tcdprogressbar"></div></div></div>' .
               $js;

      return $info;
    }

    public function getTestConnectionResult() {
      if ( MODULE_PAYMENT_SAGE_PAY_SERVER_TRANSACTION_SERVER == 'Live' ) {
        $gateway_url = 'https://live.sagepay.com/gateway/service/vspserver-register.vsp';
      } else {
        $gateway_url = 'https://test.sagepay.com/gateway/service/vspserver-register.vsp';
      }

      $params = [
        'VPSProtocol' => $this->api_version,
        'ReferrerID' => 'C74D7B82-E9EB-4FBD-93DB-76F0F551C802',
        'Vendor' => substr(MODULE_PAYMENT_SAGE_PAY_DIRECT_VENDOR_LOGIN_NAME, 0, 15),
        'Amount' => 0,
        'Currency' => DEFAULT_CURRENCY,
      ];

      $ip_address = Request::get_ip();

      if ( !empty($ip_address) && (ip2long($ip_address) != -1) && (ip2long($ip_address) != false) ) {
        $params['ClientIPAddress']= $ip_address;
      }

      $post_string = '';

      foreach ($params as $key => $value) {
        $post_string .= $key . '=' . urlencode(trim($value)) . '&';
      }

      $response = $this->sendTransactionToGateway($gateway_url, $post_string);

      if ( $response != false ) {
        return 1;
      }

      return -1;
    }

    public function sendDebugEmail($response = []) {
      if (!Text::is_empty(MODULE_PAYMENT_SAGE_PAY_SERVER_DEBUG_EMAIL)) {
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
          Notifications::mail('', MODULE_PAYMENT_SAGE_PAY_SERVER_DEBUG_EMAIL, 'Sage Pay Server Debug E-Mail', trim($email_body), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        }
      }
    }
  }
