<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class notify {

    public static function execute() {
      if (!isset($_SESSION['customer_id'])) {
        $_SESSION['navigation']->set_snapshot();

        \Href::redirect(\Guarantor::ensure_global('Linker')
          ->build('login.php'));
      }

      $notify = $_GET['products_id'] ?? $_GET['notify'] ?? $_POST['notify'];
      if (isset($notify)) {
        foreach ((array)$notify as $product_id) {
          $GLOBALS['db']->query(sprintf(<<<'EOSQL'
INSERT IGNORE INTO products_notifications
        (products_id, customers_id, date_added)
 VALUES (%d, %d, NOW())
EOSQL
            , (int)$product_id, (int)$_SESSION['customer_id']));

          $GLOBALS['messageStack']->add_session(
            'product_action',
            sprintf(PRODUCT_SUBSCRIBED, \Product::fetch_name((int)$product_id)),
            'success');
        }
      }

      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build($GLOBALS['PHP_SELF'])
        ->retain_parameters(['action', 'notify']));
    }

  }
