<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  foreach ($GLOBALS['order']->products as $product) {
// Update products_ordered (for bestsellers list)
    $GLOBALS['db']->query("UPDATE products SET products_ordered = products_ordered + " . sprintf('%d', $product['qty']) . " WHERE products_id = '" . Product::build_prid($product['id']) . "'");
  }
