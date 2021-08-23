<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_cs_redirect_old_order extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      if ( (int)MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_MINUTES > 0 ) {
        $check_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT 1
 FROM orders
 WHERE orders_id = %d AND date_purchased < DATE_SUB(NOW(), INTERVAL %d MINUTE)
EOSQL
          , (int)$GLOBALS['order_id'], (int)MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_MINUTES));

        if ( mysqli_num_rows($check_query) ) {
          Href::redirect($GLOBALS['Linker']->build('account.php'));
        }
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_STATUS' => [
          'title' => 'Enable Redirect Old Order Module',
          'value' => 'True',
          'desc' => 'Should customers be redirected when viewing old checkout success orders?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_MINUTES' => [
          'title' => 'Redirect Minutes',
          'value' => '60',
          'desc' => 'Redirect customers to the My Account page after an order older than this amount is viewed.',
        ],
        'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
