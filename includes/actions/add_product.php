<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class add_product {

    public static function execute() {
      if (isset($_POST['products_id'])) {
        $pid = (int)$_POST['products_id'];
        $attributes = $_POST['id'] ?? null;

        $qty = empty($_POST['qty']) ? 1 : (int)$_POST['qty'];

        $_SESSION['cart']->add_cart(
          $_POST['products_id'],
          $_SESSION['cart']->get_quantity(\Product::build_uprid($pid, $attributes))+$qty,
          $attributes);

        $GLOBALS['messageStack']->add_session(
          'product_action',
          sprintf(PRODUCT_ADDED, \Product::fetch_name($pid)),
          'success');
      }

      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build($GLOBALS['goto'])
        ->retain_parameters($GLOBALS['parameters']));
    }

  }
