<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class psigate extends abstract_payment_module {

    const CONFIG_KEY_BASE = 'MODULE_PAYMENT_PSIGATE_';

    public $form_action_url = 'https://order.psigate.com/psigate.asp';

    public function javascript_validation() {
      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'Local') {
        return 'if (payment_value == "' . $this->code . '") {' . "\n" .
               '  var psigate_cc_number = document.checkout_payment.psigate_cc_number.value;' . "\n" .
               '  if (psigate_cc_number == "" || psigate_cc_number.length < ' . MODULE_PAYMENT_PSIGATE_CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
               '    error_message = error_message + "' . sprintf(MODULE_PAYMENT_PSIGATE_TEXT_JS_CC_NUMBER, MODULE_PAYMENT_PSIGATE_CC_NUMBER_MIN_LENGTH) . '";' . "\n" .
               '    error = 1;' . "\n" .
               '  }' . "\n" .
               '}' . "\n";
      } else {
        return false;
      }
    }

    public function selection() {
      global $order;

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'Local') {
        for ($i = 1; $i <= 12; $i++) {
          $expires_month[] = ['id' => sprintf('%02d', $i), 'text' => strftime('%B', mktime(0, 0, 0, $i, 1, 2000))];
        }

        $today = getdate();
        for ($i = $today['year']; $i < $today['year'] + 10; $i++) {
          $expires_year[] = ['id' => strftime('%y', mktime(0, 0, 0, 1, 1, $i)), 'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))];
        }

        return [
          'id' => $this->code,
          'module' => $this->title,
          'fields' => [
            [ 'title' => MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_OWNER,
              'field' => $customer_data->get('name', $order->billing),
            ],
            [ 'title' => MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_NUMBER,
              'field' => new Input('psigate_cc_number'),
            ],
            [ 'title' => MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_EXPIRES,
              'field' => new Select('psigate_cc_expires_month', $expires_month) . '&nbsp;' . new Select('psigate_cc_expires_year', $expires_year),
            ],
          ],
        ];
      }

      return parent::selection();
    }

    public function pre_confirmation_check() {
      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE !== 'Local') {
        return false;
      }

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['psigate_cc_number'], $_POST['psigate_cc_expires_month'], $_POST['psigate_cc_expires_year']);

      $error = '';
      switch ($result) {
        case -1:
          $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
          break;
        case -2:
        case -3:
        case -4:
          $error = TEXT_CCVAL_ERROR_INVALID_DATE;
          break;
        case false:
          $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
          break;
      }

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = [
          'payment_error' => $this->code,
          'error' => $error,
          'psigate_cc_owner' => $_POST['psigate_cc_owner'],
          'psigate_cc_expires_month' => $_POST['psigate_cc_expires_month'],
          'psigate_cc_expires_year' => $_POST['psigate_cc_expires_year'],
        ];

        Href::redirect($GLOBALS['Linker']->build('checkout_payment.php', $payment_error_return));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    public function confirmation() {
      return (MODULE_PAYMENT_PSIGATE_INPUT_MODE === 'Local')
           ? [
               'title' => $this->title . ': ' . $this->cc_card_type,
               'fields' => [
                 [ 'title' => MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_OWNER,
                   'field' => $GLOBALS['customer_data']->get('name', $GLOBALS['order']->billing)],
                 [ 'title' => MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_NUMBER,
                   'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)],
                 [ 'title' => MODULE_PAYMENT_PSIGATE_TEXT_CREDIT_CARD_EXPIRES,
                   'field' => strftime('%B, %Y', mktime(0, 0, 0, $_POST['psigate_cc_expires_month'], 1, '20' . $_POST['psigate_cc_expires_year']))],
               ],
             ]
           : false;
    }

    public function process_button() {
      global $currencies, $customer_data, $order;

      switch (MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE) {
        case 'Always Good':
          $transaction_mode = '1';
          break;
        case 'Always Duplicate':
          $transaction_mode = '2';
          break;
        case 'Always Decline':
          $transaction_mode = '3';
          break;
        case 'Production':
        default:
          $transaction_mode = '0';
          break;
      }

      switch (MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE) {
        case 'Sale':
          $transaction_type = '0';
          break;
        case 'PostAuth':
          $transaction_type = '2';
          break;
        case 'PreAuth':
        default:
          $transaction_type = '1';
          break;
      }

      $process_button_string = new Input('MerchantID', ['value' => MODULE_PAYMENT_PSIGATE_MERCHANT_ID], 'hidden')
                             . new Input('FullTotal', ['value' => number_format($order->info['total'] * $currencies->get_value(MODULE_PAYMENT_PSIGATE_CURRENCY), $currencies->currencies[MODULE_PAYMENT_PSIGATE_CURRENCY]['decimal_places'])], 'hidden')
                             . new Input('ThanksURL', ['value' => $GLOBALS['Linker']->build('checkout_process.php')], 'hidden')
                             . new Input('NoThanksURL', ['value' => $GLOBALS['Linker']->build('checkout_payment.php', ['payment_error' => $this->code])], 'hidden')
                             . new Input('Bname', ['value' => $customer_data->get('name', $order->billing)], 'hidden')
                             . new Input('Baddr1', ['value' => $customer_data->get('street_address', $order->billing)], 'hidden')
                             . new Input('Bcity', ['value' => $customer_data->get('city', $order->billing)], 'hidden');

      if ($customer_data->get('country_iso_code_2', $order->billing) === 'US') {
        $process_button_string .= new Input('Bstate', ['value' => Zone::fetch_code($customer_data->get('country_id', $order->billing), $customer_data->get('zone_id', $order->billing), '')], 'hidden');
      } else {
        $process_button_string .= new Input('Bstate', ['value' => $customer_data->get('state', $order->billing)], 'hidden');
      }

      $process_button_string .= new Input('Bzip', ['value' => $customer_data->get('postcode', $order->billing)], 'hidden')
                              . new Input('Bcountry', ['value' => $customer_data->get('country_iso_code_2', $order->billing)], 'hidden')
                              . new Input('Phone', ['value' => $customer_data->get('telephone', $order->customer)], 'hidden')
                              . new Input('Email', ['value' => $customer_data->get('email_address', $order->customer)], 'hidden')
                              . new Input('Sname', ['value' => $customer_data->get('name', $order->delivery)], 'hidden')
                              . new Input('Saddr1', ['value' => $customer_data->get('street_address', $order->delivery)], 'hidden')
                              . new Input('Scity', ['value' => $customer_data->get('city', $order->delivery)], 'hidden')
                              . new Input('Sstate', ['value' => $customer_data->get('state', $order->delivery)], 'hidden')
                              . new Input('Szip', ['value' => $customer_data->get('postcode', $order->delivery)], 'hidden')
                              . new Input('Scountry', ['value' => $customer_data->get('country_iso_code_2', $order->delivery)], 'hidden')
                              . new Input('ChargeType', ['value' => $transaction_type], 'hidden')
                              . new Input('Result', ['value' => $transaction_mode], 'hidden')
                              . new Input('IP',['value' =>  $_SERVER['REMOTE_ADDR']], 'hidden');

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'Local') {
        $process_button_string .= new Input('CardNumber', ['value' => $this->cc_card_number], 'hidden')
                                . new Input('ExpMonth', ['value' => $this->cc_expiry_month], 'hidden')
                                . new Input('ExpYear', ['value' => substr($this->cc_expiry_year, -2)], 'hidden');
      }

      return $process_button_string;
    }

    public function get_error() {
      if (isset($_GET['ErrMsg']) && !Text::is_empty($_GET['ErrMsg'])) {
        $error = $_GET['ErrMsg'];
      } elseif (isset($_GET['Err']) && !Text::is_empty($_GET['Err'])) {
        $error = $_GET['Err'];
      } elseif (isset($_GET['error']) && !Text::is_empty($_GET['error'])) {
        $error = $_GET['error'];
      } else {
        $error = MODULE_PAYMENT_PSIGATE_TEXT_ERROR_MESSAGE;
      }

      return [
        'title' => MODULE_PAYMENT_PSIGATE_TEXT_ERROR,
        'error' => $error,
      ];
    }

    public function get_parameters() {
      return [
        'MODULE_PAYMENT_PSIGATE_STATUS' => [
          'title' => 'Enable PSiGate Module',
          'value' => 'True',
          'desc' => 'Do you want to accept PSiGate payments?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_PSIGATE_MERCHANT_ID' => [
          'title' => 'Merchant ID',
          'value' => 'teststorewithcard',
          'desc' => 'Merchant ID used for the PSiGate service',
        ],
        'MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE' => [
          'title' => 'Transaction Mode',
          'value' => 'Always Good',
          'desc' => 'Transaction mode to use for the PSiGate service',
          'set_func' => "Config::select_one(['Production', 'Always Good', 'Always Duplicate', 'Always Decline'], ",
        ],
        'MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE' => [
          'title' => 'Transaction Type',
          'value' => 'PreAuth',
          'desc' => 'Transaction type to use for the PSiGate service',
          'set_func' => "Config::select_one(['Sale', 'PreAuth', 'PostAuth'], ",
        ],
        'MODULE_PAYMENT_PSIGATE_INPUT_MODE' => [
          'title' => 'Credit Card Collection',
          'value' => 'Local',
          'desc' => 'Should the credit card details be collected locally or remotely at PSiGate?',
          'set_func' => "Config::select_one(['Local', 'Remote'], ",
        ],
        'MODULE_PAYMENT_PSIGATE_CURRENCY' => [
          'title' => 'Transaction Currency',
          'value' => 'USD',
          'desc' => 'The currency to use for credit card transactions',
          'set_func' => "Config::select_one(['CAD', 'USD'], ",
        ],
        'MODULE_PAYMENT_PSIGATE_SORT_ORDER' => [
          'title' => 'Sort order of display.',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        'MODULE_PAYMENT_PSIGATE_CC_NUMBER_MIN_LENGTH' => [
          'title' => 'Credit Card Number',
          'value' => '10',
          'desc' => 'Minimum length of credit card number',
        ],
        'MODULE_PAYMENT_PSIGATE_ZONE' => [
          'title' => 'Payment Zone',
          'value' => '0',
          'desc' => 'If a zone is selected, only enable this payment method for that zone.',
          'use_func' => 'geo_zone::fetch_name',
          'set_func' => 'Config::select_geo_zone(',
        ],
        'MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID' => [
          'title' => 'Set Order Status',
          'value' => '0',
          'desc' => 'Set the status of orders made with this payment module to this value',
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
      ];
    }

  }
