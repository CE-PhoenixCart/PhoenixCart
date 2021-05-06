<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cd_password_reset extends abstract_customer_data_module {

    const CONFIG_KEY_BASE = 'MODULE_CUSTOMER_DATA_PASSWORD_RESET_';

    const PROVIDES = [ 'password_reset_key', 'password_reset_date' ];
    const REQUIRES = [  ];

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable Password Reset module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_option(['True', 'False'], ",
        ],
      ];
    }

    public function get($field, &$customer_details) {
      switch ($field) {
        case 'password_reset_key':
        case 'password_reset_date':
          return $customer_details[$field] ?? null;
      }
    }

    public function build_db_values(&$db_tables, $customer_details, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'customers_info');
      $db_tables['customers_info']['password_reset_key'] = $customer_details['password_reset_key'];
      $db_tables['customers_info']['password_reset_date'] = $customer_details['password_reset_date'];
    }

    public function build_db_aliases(&$db_tables, $table = 'both') {
      Guarantor::guarantee_subarray($db_tables, 'customers_info');
      $db_tables['customers_info']['password_reset_key'] = null;
      $db_tables['customers_info']['password_reset_date'] = null;
    }

  }
