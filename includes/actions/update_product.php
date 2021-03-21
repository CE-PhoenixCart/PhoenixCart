<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  namespace Phoenix\Actions;

  class update_product {

    public static function execute() {
      foreach (($_POST['products_id'] ?? []) as $i => $product_id) {
        if (in_array($product_id, ($_POST['cart_delete'] ?? []))) {
          $_SESSION['cart']->remove($product_id);

          $GLOBALS['messageStack']->add_session(
            'product_action',
            sprintf(PRODUCT_REMOVED, \Product::fetch_name($product_id)),
            'warning');
        } else {
          $attributes = $_POST['id'][$product_id] ?? null;
          $_SESSION['cart']->add_cart(
            $product_id,
            $_POST['cart_quantity'][$i],
            $attributes,
            false);
        }
      }

      \Href::redirect(\Guarantor::ensure_global('Linker')
        ->build($GLOBALS['goto'])
        ->retain_parameters($GLOBALS['parameters']));
    }

  }
