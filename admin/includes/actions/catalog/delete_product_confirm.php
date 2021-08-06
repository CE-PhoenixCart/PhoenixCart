<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_POST['products_id'], $_POST['product_categories']) && is_array($_POST['product_categories'])) {
    $product_id = Text::input($_POST['products_id']);
    $product_categories = implode(', ', array_map('intval', $_POST['product_categories']));

    $db->query("DELETE FROM products_to_categories WHERE products_id = " . (int)$product_id . " AND categories_id IN (" . $product_categories . ")");

    $product_categories_query = $db->query("SELECT COUNT(*) AS total FROM products_to_categories WHERE products_id = " . (int)$product_id);
    $product_categories = $product_categories_query->fetch_assoc();

    if ($product_categories['total'] == '0') {
      Products::remove($product_id);
    }
  }

  return $Admin->link('catalog.php', ['cPath' => $cPath]);
