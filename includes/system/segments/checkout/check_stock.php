<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (STOCK_CHECK === 'true') {
    $any_out_of_stock = false;
    foreach ($order->products as $product) {
      if (product_by_id::build(Product::build_prid($product['id']))->lacks_stock($product['qty'])) {
        $any_out_of_stock = true;
      }
    }

    // Out of Stock
    if ( $any_out_of_stock && (STOCK_ALLOW_CHECKOUT !== 'true') ) {
      Href::redirect($GLOBALS['Linker']->build('shopping_cart.php'));
    }
  }
