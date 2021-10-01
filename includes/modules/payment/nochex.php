<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class nochex extends abstract_payment_module {

    const CONFIG_KEY_BASE = 'MODULE_PAYMENT_NOCHEX_';

    public $form_action_url = 'https://www.nochex.com/nochex.dll/checkout';

// class methods
    function process_button() {
      return new Input('cmd', ['value' => '_xclick'], 'hidden')
           . new Input('email', ['value' => MODULE_PAYMENT_NOCHEX_ID], 'hidden')
           . new Input('amount', ['value' => $GLOBALS['currencies']->format_raw($GLOBALS['order']->info['total'], true, 'GBP')], 'hidden')
           . new Input('ordernumber', ['value' => $_SESSION['customer_id'] . '-' . date('Ymdhis')], 'hidden')
           . new Input('returnurl', ['value' => $GLOBALS['Linker']->build('checkout_process.php')], 'hidden')
           . new Input('cancel_return', ['value' => $GLOBALS['Linker']->build('checkout_payment.php')], 'hidden');
    }

    protected function get_parameters() {
      return [
        'MODULE_PAYMENT_NOCHEX_STATUS' => [
          'title' => 'Enable NOCHEX Module',
          'value' => 'True',
          'desc' => 'Do you want to accept NOCHEX payments?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_NOCHEX_ID' => [
          'title' => 'E-Mail Address',
          'value' => 'you@yourbusiness.com',
          'desc' => 'The e-mail address to use for the NOCHEX service',
        ],
        'MODULE_PAYMENT_NOCHEX_SORT_ORDER' => [
          'title' => 'Sort order of display.',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        'MODULE_PAYMENT_NOCHEX_ZONE' => [
          'title' => 'Payment Zone',
          'value' => '0',
          'desc' => 'If a zone is selected, only enable this payment method for that zone.',
          'use_func' => 'geo_zone::fetch_name',
          'set_func' => 'Config::select_geo_zone(',
        ],
        'MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID' => [
          'title' => 'Set Order Status',
          'value' => '0',
          'desc' => 'Set the status of orders made with this payment module to this value (if non-zero)',
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
      ];
    }

  }
