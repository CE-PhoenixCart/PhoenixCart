<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Login {

    public static function add_customer_id() {
      $GLOBALS['customer'] = new customer($GLOBALS['customer_data']->get('id', $GLOBALS['customer_details']));
      $_SESSION['customer_id'] = $GLOBALS['customer']->get_id();
      $GLOBALS['customer_id'] =& $_SESSION['customer_id'];
    }

    public static function hook() {
      $GLOBALS['hooks']->cat('postLogin');
    }

    public static function log() {
      $GLOBALS['db']->query(sprintf(<<<'EOSQL'
UPDATE customers_info
 SET customers_info_date_of_last_logon = NOW(),
     customers_info_number_of_logons = customers_info_number_of_logons + 1,
     password_reset_key = null,
     password_reset_date = null
 WHERE customers_info_id = %d
EOSQL
        , (int)$_SESSION['customer_id']));
    }

    public static function notify() {
      Notifications::notify('create_account', $GLOBALS['customer']);
    }

    public static function redirect_success() {
      Href::redirect($GLOBALS['Linker']->build('create_account_success.php'));
    }

    public static function require($parameters = null) {
      if (!isset($_SESSION['customer_id'])) {
        $_SESSION['navigation']->set_snapshot($parameters);
        Href::redirect($GLOBALS['Linker']->build('login.php'));
      }
    }

    public static function set_customer_id() {
      $_SESSION['customer_id'] = $GLOBALS['login_customer_id'];
    }

  }
