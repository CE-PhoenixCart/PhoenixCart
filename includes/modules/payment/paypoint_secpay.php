<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class paypoint_secpay extends abstract_payment_module {

    const CONFIG_KEY_BASE = 'MODULE_PAYMENT_PAYPOINT_SECPAY_';

    private $signature = 'paypoint|paypoint_secpay|1.0|2.3';
    public $form_action_url = 'https://www.secpay.com/java-bin/ValCard';

    public function process_button() {
      global $order, $currencies, $customer_data;

      switch (MODULE_PAYMENT_PAYPOINT_SECPAY_CURRENCY) {
        case 'Default Currency':
          $sec_currency = DEFAULT_CURRENCY;
          break;
        case 'Any Currency':
        default:
          $sec_currency = $_SESSION['currency'];
          break;
      }

      switch (MODULE_PAYMENT_PAYPOINT_SECPAY_TEST_STATUS) {
        case 'Always Fail':
          $test_status = 'false';
          break;
        case 'Production':
          $test_status = 'live';
          break;
        case 'Always Successful':
        default:
          $test_status = 'true';
          break;
      }

// Calculate the digest to send to SECPAY

      $digest_string = STORE_NAME . date('Ymdhis') . $currencies->format_raw($order->info['total'], true, $sec_currency) . MODULE_PAYMENT_PAYPOINT_SECPAY_REMOTE;

// There is a bug in the digest code, if there are any spaces in the trans id (usually in the STORE_NAME)
// SECPay will replace these with an _ and the hash is calculated from that
// so need to do a search and replace in the digest_string for spaces and replace with _
      $digest = md5(str_replace(' ', '_', $digest_string));

// In case this gets 'fixed' at the SECPay end do a search and replace on the trans_id too
      $trans_id = str_replace(' ', '_', STORE_NAME . date('Ymdhis'));

      $error_link = $GLOBALS['Linker']->build('checkout_payment.php', ['payment_error' => $this->code], false);

      $customer_data->get('country', $order->billing);
      $customer_data->get('country', $order->delivery);
      return new Input('merchant', ['value' => MODULE_PAYMENT_PAYPOINT_SECPAY_MERCHANT_ID], 'hidden')
           . new Input('trans_id', ['value' => $trans_id], 'hidden')
           . new Input('amount', ['value' => $currencies->format_raw($order->info['total'], true, $sec_currency)], 'hidden')
           . new Input('bill_name', ['value' => $customer_data->get('name', $order->billing)], 'hidden')
           . new Input('bill_addr_1', ['value' => $customer_data->get('street_address', $order->billing)], 'hidden')
           . new Input('bill_addr_2', ['value' => $customer_data->get('suburb', $order->billing)], 'hidden')
           . new Input('bill_city', ['value' => $customer_data->get('city', $order->billing)], 'hidden')
           . new Input('bill_state', ['value' => $customer_data->get('state', $order->billing)], 'hidden')
           . new Input('bill_post_code', ['value' => $customer_data->get('postcode', $order->billing)], 'hidden')
           . new Input('bill_country', ['value' => $customer_data->get('country_name', $order->billing)], 'hidden')
           . new Input('bill_tel', ['value' => $customer_data->get('telephone', $order->customer)], 'hidden')
           . new Input('bill_email', ['value' => $customer_data->get('email_address', $order->customer)], 'hidden')
           . new Input('ship_name', ['value' => $customer_data->get('name', $order->delivery)], 'hidden')
           . new Input('ship_addr_1', ['value' => $customer_data->get('street_address', $order->delivery)], 'hidden')
           . new Input('ship_addr_2', ['value' => $customer_data->get('suburb', $order->delivery)], 'hidden')
           . new Input('ship_city', ['value' => $customer_data->get('city', $order->delivery)], 'hidden')
           . new Input('ship_state', ['value' => $customer_data->get('state', $order->delivery)], 'hidden')
           . new Input('ship_post_code', ['value' => $customer_data->get('postcode', $order->delivery)], 'hidden')
           . new Input('ship_country',['value' =>  $customer_data->get('country_name', $order->delivery)], 'hidden')
           . new Input('currency', ['value' => $sec_currency], 'hidden')
           . new Input('callback', ['value' => $GLOBALS['Linker']->build('checkout_process.php', [], false) . ';' . $error_link], 'hidden')
           . new Input(session_name(), ['value' => session_id()], 'hidden')
           . new Input('options', ['value' => 'test_status=' . $test_status . ',dups=false,cb_flds=' . session_name()], 'hidden')
           . new Input('digest', ['value' => $digest], 'hidden');
    }

    public function before_process() {
      if ( ($_GET['valid'] == 'true') && ($_GET['code'] == 'A') && !empty($_GET['auth_code']) && empty($_GET['resp_code']) && !empty($_GET[session_name()]) ) {
        $DIGEST_PASSWORD = MODULE_PAYMENT_PAYPOINT_SECPAY_READERS_DIGEST;
        list($REQUEST_URI, $CHECK_SUM) = explode('hash=', $_SERVER['REQUEST_URI']);

        if ($_GET['hash'] != md5($REQUEST_URI . $DIGEST_PASSWORD)) {
          Href::redirect($link->set_parameter('detail', 'hash'));
        }
      } else {
        Href::redirect($link);
      }
    }

    public function get_error() {
      if ($_GET['code'] == 'N') {
        $error = MODULE_PAYMENT_PAYPOINT_SECPAY_TEXT_ERROR_MESSAGE_N;
      } elseif ($_GET['code'] == 'C') {
        $error = MODULE_PAYMENT_PAYPOINT_SECPAY_TEXT_ERROR_MESSAGE_C;
      } else {
        $error = MODULE_PAYMENT_PAYPOINT_SECPAY_TEXT_ERROR_MESSAGE;
      }

      return [
        'title' => MODULE_PAYMENT_PAYPOINT_SECPAY_TEXT_ERROR,
        'error' => $error,
      ];
    }

    protected function get_parameters() {
      return [
        'MODULE_PAYMENT_PAYPOINT_SECPAY_STATUS' => [
          'title' => 'Enable PayPoint.net SECPay Module',
          'value' => 'False',
          'desc' => 'Do you want to accept PayPoint.net SECPay payments?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_PAYPOINT_SECPAY_MERCHANT_ID' => [
          'title' => 'Merchant ID',
          'value' => 'secpay',
          'desc' => 'Merchant ID to use for the SECPay service',
        ],
        'MODULE_PAYMENT_PAYPOINT_SECPAY_CURRENCY' => [
          'title' => 'Transaction Currency',
          'value' => 'Any Currency',
          'desc' => 'The currency to use for credit card transactions',
          'set_func' => "Config::select_one(['Any Currency', 'Default Currency'], ",
        ],
        'MODULE_PAYMENT_PAYPOINT_SECPAY_TEST_STATUS' => [
          'title' => 'Transaction Mode',
          'value' => 'Always Successful',
          'desc' => 'Transaction mode to use for the PayPoint.net SECPay service',
          'set_func' => "Config::select_one(['Always Successful', 'Always Fail', 'Production'], ",
        ],
        'MODULE_PAYMENT_PAYPOINT_SECPAY_SORT_ORDER' => [
          'title' => 'Sort order of display.',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        'MODULE_PAYMENT_PAYPOINT_SECPAY_ZONE' => [
          'title' => 'Payment Zone',
          'value' => '0',
          'desc' => 'If a zone is selected, only enable this payment method for that zone.',
          'use_func' => 'geo_zone::fetch_name',
          'set_func' => 'Config::select_geo_zone(',
        ],
        'MODULE_PAYMENT_PAYPOINT_SECPAY_ORDER_STATUS_ID' => [
          'title' => 'Set Order Status',
          'value' => '0',
          'desc' => 'Set the status of orders made with this payment module to this value',
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
        'MODULE_PAYMENT_PAYPOINT_SECPAY_REMOTE' => [
          'title' => 'Remote Password',
          'value' => 'secpay',
          'desc' => 'The Remote Password needs to be created in the PayPoint extranet.',
        ],
        'MODULE_PAYMENT_PAYPOINT_SECPAY_READERS_DIGEST' => [
          'title' => 'Digest Key',
          'value' => 'secpay',
          'desc' => 'The Digest Key needs to be created in the PayPoint extranet.',
        ],
      ];
    }

  }
