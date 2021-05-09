<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Customers {

    const PAGES = [
      'account_edit',
      'account_newsletters',
      'account_password',
      'address_book',
      'checkout_new_address',
      'create_account',
      'customers',
    ];

    public static function generate() {
      $customer_data =& Guarantor::ensure_global('customer_data');

      $query = $GLOBALS['db']->query($customer_data->add_order_by(
        $customer_data->build_read([ 'id', 'sortable_name'], 'customers'), ['sortable_name']));
      while ($customer_details = $query->fetch_assoc()) {
        yield [
          'id' => $customer_data->get('id', $customer_details),
          'text' => $customer_data->get('sortable_name', $customer_details),
        ];
      }
    }

    public static function select($name, $parameters = [], $selected = '', $class = 'form-control') {
      return (new Select($name, array_merge(
        [['id' => '', 'text' => '--- ' . IMAGE_SELECT . ' ---']],
        iterator_to_array(static::generate(), false)
      ), $parameters))->append_css($class)->set_selection($selected);
    }

    public static function select_pages($key_values, $key_name = null) {
      $pages = static::PAGES;

      $parameters = ['pages' => &$pages];
      $GLOBALS['admin_hooks']->cat('accountEditPages', $parameters);

      return Config::select_multiple($pages, $key_values, $key_name);
    }

  }
