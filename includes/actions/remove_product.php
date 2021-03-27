<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class remove_product {

    public static function execute() {
      if (isset($_GET['products_id'])) {
        $_SESSION['cart']->remove($_GET['products_id']);

        $GLOBALS['messageStack']->add_session('product_action', sprintf(PRODUCT_REMOVED, \Product::fetch_name($_GET['products_id'])), 'warning');
      }

      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build($GLOBALS['goto'])
        ->retain_parameters($GLOBALS['parameters']));
    }

  }
