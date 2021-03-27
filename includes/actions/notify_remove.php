<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class notify_remove {

    public static function execute() {
      if (!isset($_SESSION['customer_id'])) {
        $_SESSION['navigation']->set_snapshot();

        \Href::redirect(\Guarantor::ensure_global('Linker')
          ->build('login.php'));
      }

      if (isset($_GET['products_id'])) {
        $GLOBALS['db']->query(sprintf(<<<'EOSQL'
DELETE FROM products_notifications WHERE products_id = %d AND customers_id = %d
EOSQL
          , (int)$_GET['products_id'], (int)$_SESSION['customer_id']));
        $GLOBALS['messageStack']->add_session(
          'product_action',
          sprintf(PRODUCT_UNSUBSCRIBED, \Product::fetch_name((int)$_GET['products_id'])),
          'warning');

        \Href::redirect(\Guarantor::ensure_global('Linker')
          ->build($GLOBALS['PHP_SELF'])
          ->retain_parameters(['action']));
      }
    }

  }
