<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class moneyorder extends abstract_payment_module {

    const CONFIG_KEY_BASE = 'MODULE_PAYMENT_MONEYORDER_';

    public $email_footer;

    public function __construct() {
      parent::__construct();

      if ( !defined('MODULE_PAYMENT_MONEYORDER_PAYTO') || Text::is_empty(MODULE_PAYMENT_MONEYORDER_PAYTO)) {
        $this->description .= '<div class="alert alert-warning">' . MODULE_PAYMENT_MONEYORDER_WARNING_SETUP . '</div>';
      }

      $this->email_footer = sprintf(MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER,
        ($this->base_constant('PAYTO') ?? ''),
        STORE_NAME, nl2br(STORE_ADDRESS));
    }

    public function confirmation() {
      return [
        'title' => sprintf(MODULE_PAYMENT_MONEYORDER_TEXT_CONFIRMATION,
                           (self::get_constant('MODULE_PAYMENT_MONEYORDER_PAYTO') ?? ''),
                           STORE_NAME, STORE_ADDRESS),
      ];
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Check/Money Order Module',
          'value' => 'True',
          'desc' => 'Do you want to accept Check/Money Order payments?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'PAYTO' => [
          'title' => 'Make Payable to:',
          'value' => '',
          'desc' => 'Who should payments be made payable to?',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort order of display.',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        $this->config_key_base . 'ZONE' => [
          'title' => 'Payment Zone',
          'value' => '0',
          'desc' => 'If a zone is selected, only enable this payment method for that zone.',
          'use_func' => 'geo_zone::fetch_name',
          'set_func' => 'Config::select_geo_zone(',
        ],
        $this->config_key_base . 'ORDER_STATUS_ID' => [
          'title' => 'Set Order Status',
          'value' => '0',
          'desc' => 'Set the status of orders made with this payment module to this value',
          'set_func' => 'Config::select_order_status(',
          'use_func' => 'order_status::fetch_name',
        ],
      ];
    }

  }
