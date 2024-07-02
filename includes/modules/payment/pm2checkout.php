<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class pm2checkout extends abstract_payment_module {

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

    const CONFIG_KEY_BASE = 'MODULE_PAYMENT_2CHECKOUT_';

    private $signature = '2checkout|pm2checkout|1.2|2.2';

    public $form_action_url = 'https://www.2checkout.com/2co/buyer/purchase';

    public function pre_confirmation_check() {
      if (MODULE_PAYMENT_2CHECKOUT_ROUTINE == 'Single-Page') {
        $this->form_action_url = 'https://www.2checkout.com/checkout/spurchase';
      }
    }

    public function process_button() {
      global $order, $customer_data, $currencies;

      $process_button = new Input('sid', ['type' => 'hidden', 'value' => MODULE_PAYMENT_2CHECKOUT_LOGIN])
                      . new Input('total', ['type' => 'hidden', 'value' => $currencies->format_raw($order->info['total'], true, MODULE_PAYMENT_2CHECKOUT_CURRENCY)])
                      . new Input('cart_order_id', ['type' => 'hidden', 'value' => date('YmdHis') . '-' . $_SESSION['customer_id'] . '-' . $_SESSION['cartID']])
                      . new Input('fixed', ['type' => 'hidden', 'value' => 'Y'])
                      . new Input('first_name', ['type' => 'hidden', 'value' => $customer_data->get('firstname', $order->billing)])
                      . new Input('last_name', ['type' => 'hidden', 'value' => $customer_data->get('lastname', $order->billing)])
                      . new Input('street_address', ['type' => 'hidden', 'value' => $customer_data->get('street_address', $order->billing)])
                      . new Input('city', ['type' => 'hidden', 'value' => $customer_data->get('city', $order->billing)])
                      . new Input('state', ['type' => 'hidden', 'value' => $customer_data->get('state', $order->billing)])
                      . new Input('zip', ['type' => 'hidden', 'value' => $customer_data->get('postcode', $order->billing)])
                      . new Input('country', ['type' => 'hidden', 'value' => $customer_data->get('country_name', $order->billing)])
                      . new Input('email', ['type' => 'hidden', 'value' => $customer_data->get('email_address', $order->customer)])
                      . new Input('phone', ['type' => 'hidden', 'value' => $customer_data->get('telephone', $order->customer)])
                      . new Input('ship_name', ['type' => 'hidden', 'value' => $customer_data->get('name', $order->delivery)])
                      . new Input('ship_street_address', ['type' => 'hidden', 'value' => $customer_data->get('street_address', $order->delivery)])
                      . new Input('ship_city', ['type' => 'hidden', 'value' => $customer_data->get('city', $order->delivery)])
                      . new Input('ship_state', ['type' => 'hidden', 'value' => $customer_data->get('state', $order->delivery)])
                      . new Input('ship_zip', ['type' => 'hidden', 'value' => $customer_data->get('postcode', $order->delivery)])
                      . new Input('ship_country', ['type' => 'hidden', 'value' => $customer_data->get('country_name', $order->delivery)]);

      foreach ($order->products as $i => $product) {
        $i++;
        $process_button .= new Input("c_prod_$i", ['type' => 'hidden', 'value' => (int)$product['id'] . ',' . (int)$product['qty']])
                         . new Input("c_name_$i", ['type' => 'hidden', 'value' => $product['name']])
                         . new Input("c_description_$i", ['type' => 'hidden', 'value' => $product['name']])
                         . new Input("c_price_$i", ['type' => 'hidden', 'value' => $currencies->format_raw(Tax::price($product['final_price'], $product['tax']), true, MODULE_PAYMENT_2CHECKOUT_CURRENCY)]);
      }

      $process_button .= new Input('id_type', ['type' => 'hidden', 'value' => '1'])
                       . new Input('skip_landing', ['type' => 'hidden', 'value' => '1']);

      if ('Test' === MODULE_PAYMENT_2CHECKOUT_TESTMODE) {
        $process_button .= new Input('demo', ['type' => 'hidden', 'value' => 'Y']);
      }

      $process_button .= new Input('return_url', ['type' => 'hidden', 'value' => Guarantor::ensure_global('Linker')->build('shopping_cart.php')]);

      $lang_query = $GLOBALS['db']->query("SELECT code FROM languages WHERE languages_id = " . (int)$_SESSION['languages_id']);
      $lang = $lang_query->fetch_assoc();

      switch (strtolower($lang['code'])) {
        case 'es':
          $process_button .= new Input('lang', ['type' => 'hidden', 'value' => 'sp']);
          break;
      }

      $process_button .= new Input('cart_brand_name', ['type' => 'hidden', 'value' => PROJECT_VERSION])
                       . new Input('cart_version_name', ['type' => 'hidden', 'value' => Versions::get()]);

      return $process_button;
    }

    public function before_process() {
      if (!in_array(Request::value('credit_card_processed'), ['Y', 'K'])) {
        Href::redirect(Guarantor::ensure_global('Linker')->build('checkout_payment.php', ['payment_error' => $this->code]));
      }
    }

    public function after_process() {
      global $order;

      if ('Test' === MODULE_PAYMENT_2CHECKOUT_TESTMODE) {
        $sql_data = [
          'orders_id' => (int)$order->get_id(),
          'orders_status_id' => (int)$order->info['order_status'],
          'date_added' => 'NOW()',
          'customer_notified' => '0',
          'comments' => MODULE_PAYMENT_2CHECKOUT_TEXT_WARNING_DEMO_MODE,
        ];

        $GLOBALS['db']->perform('orders_status_history', $sql_data);
      } elseif (!Text::is_empty(MODULE_PAYMENT_2CHECKOUT_SECRET_WORD) && (MODULE_PAYMENT_2CHECKOUT_TESTMODE === 'Production')) {
// The KEY value returned from the gateway is intentionally broken for Test transactions so it is only checked in Production mode
        $key = md5(MODULE_PAYMENT_2CHECKOUT_SECRET_WORD
                 . MODULE_PAYMENT_2CHECKOUT_LOGIN
                 . $order->get_id()
                 . $GLOBALS['currencies']->format_raw($order->info['total'], true, MODULE_PAYMENT_2CHECKOUT_CURRENCY));
        if ((Request::value('order_number') != $order->get_id()) || strtoupper($key) !== strtoupper(Request::value('key'))) {
          $sql_data = [
            'orders_id' => (int)$order->get_id(),
            'orders_status_id' => (int)$order->info['order_status'],
            'date_added' => 'NOW()',
            'customer_notified' => '0',
            'comments' => MODULE_PAYMENT_2CHECKOUT_TEXT_WARNING_TRANSACTION_ORDER,
          ];

          $GLOBALS['db']->perform('orders_status_history', $sql_data);
        }
      }
    }

    public function get_error() {
      return [
        'title' => '',
        'error' => MODULE_PAYMENT_2CHECKOUT_TEXT_ERROR_MESSAGE,
      ];
    }

    protected function get_parameters() {
      return [
        'MODULE_PAYMENT_2CHECKOUT_STATUS' => [
          'title' => 'Enable 2Checkout',
          'value' => 'False',
          'desc' => 'Do you want to accept 2CheckOut payments?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_PAYMENT_2CHECKOUT_LOGIN' => [
          'title' => 'Vendor Account',
          'value' => '',
          'desc' => 'The vendor account number for the 2Checkout gateway.',
        ],
        'MODULE_PAYMENT_2CHECKOUT_TESTMODE' => [
          'title' => 'Transaction Mode',
          'value' => 'Test',
          'desc' => 'Transaction mode used for the 2Checkout gateway.',
          'set_func' => "Config::select_one(['Test', 'Production'], ",
        ],
        'MODULE_PAYMENT_2CHECKOUT_SECRET_WORD' => [
          'title' => 'Secret Word',
          'value' => '',
          'desc' => 'The secret word to confirm transactions with. (Must be the same as defined on the Vendor Admin interface)',
        ],
        'MODULE_PAYMENT_2CHECKOUT_ROUTINE' => [
          'title' => 'Payment Routine',
          'value' => 'Multi-Page',
          'desc' => 'The payment routine to use on the 2Checkout gateway.',
          'set_func' => "Config::select_one(['Multi-Page', 'Single-Page'], ",
        ],
        'MODULE_PAYMENT_2CHECKOUT_CURRENCY' => [
          'title' => 'Processing Currency',
          'value' => DEFAULT_CURRENCY,
          'desc' => 'The currency to process transactions in. (Must be the same as defined on the Vendor Admin interface)',
          'set_func' => 'pm2checkout::getCurrencies(',
        ],
        'MODULE_PAYMENT_2CHECKOUT_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. (Lowest is displayed first)',
        ],
        'MODULE_PAYMENT_2CHECKOUT_ZONE' => [
          'title' => 'Payment Zone',
          'value' => '0',
          'desc' => 'If a zone is selected, only enable this payment method for that zone.',
          'use_func' => 'geo_zone::fetch_name',
          'set_func' => 'Config::select_geo_zone(',
        ],
        'MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID' => [
          'title' => 'Set Order Status',
          'value' => '0',
          'desc' => 'Set the status of orders made with this payment module to this value.',
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
      ];
    }

    public static function getCurrencies($value, $key = '') {
      return new Select(
        ($key ? 'configuration[' . $key . ']' : 'configuration_value'),
        $GLOBALS['db']->fetch_all("SELECT code AS id, title AS text FROM currencies ORDER BY title"),
        ['value' => $value]);
    }

  }
