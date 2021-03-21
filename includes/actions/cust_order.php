<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class cust_order {

    public static function execute() {
      if (isset($_SESSION['customer_id'], $_GET['pid'])) {
        $pid = (int)$_GET['pid'];

        if (\product_by_id::build($pid)->get('has_attributes')) {
          \Href::redirect(\Guarantor::ensure_global('Linker')
            ->build('product_info.php', 'products_id=' . $pid));
        }

        $_SESSION['cart']->add_cart($pid, $_SESSION['cart']->get_quantity($pid)+1);
        $GLOBALS['messageStack']->add_session('product_action', sprintf(PRODUCT_ADDED, \Product::fetch_name($pid)), 'success');
      }

      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build($GLOBALS['goto'])
        ->retain_parameters($GLOBALS['parameters']));
    }

  }
