<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_robot_noindex extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_ROBOT_NOINDEX_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      if (!Text::is_empty(MODULE_HEADER_TAGS_ROBOT_NOINDEX_PAGES)) {
        if (in_array(basename(Request::get_page()), page_selection::_get_pages($this->base_constant('PAGES')))) {
          $GLOBALS['Template']->add_block('<meta name="robots" content="noindex,follow" />', $this->group);
        }
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_ROBOT_NOINDEX_STATUS' => [
          'title' => 'Enable Robot NoIndex Module',
          'value' => 'True',
          'desc' => 'Do you want to enable the Robot NoIndex module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_ROBOT_NOINDEX_PAGES' => [
          'title' => 'Pages',
          'value' => 'account.php;account_edit.php;account_history.php;'
                   . 'account_history_info.php;account_newsletters.php;'
                   . 'account_notifications.php;account_password.php;'
                   . 'address_book.php;address_book_process.php;'
                   . 'checkout_confirmation.php;checkout_payment.php;'
                   . 'checkout_payment_address.php;checkout_process.php;'
                   . 'checkout_shipping.php;checkout_shipping_address.php;'
                   . 'checkout_success.php;cookie_usage.php;create_account.php;'
                   . 'create_account_success.php;login.php;logoff.php;'
                   . 'password_forgotten.php;password_reset.php;'
                   . 'shopping_cart.php;ssl_check.php',
          'desc' => 'The pages to add the meta robot noindex tag to.',
          'use_func' => 'page_selection::_show_pages',
          'set_func' => 'page_selection::_edit_pages(',
        ],
        'MODULE_HEADER_TAGS_ROBOT_NOINDEX_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
