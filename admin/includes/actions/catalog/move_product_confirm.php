<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $products_id = Text::input($_POST['products_id']);
  $new_parent_id = Text::input($_POST['move_to_category_id']);

  $duplicate_check_query = $db->query("SELECT COUNT(*) AS total FROM products_to_categories WHERE products_id = " . (int)$products_id . " AND categories_id = " . (int)$new_parent_id);
  $duplicate_check = $duplicate_check_query->fetch_assoc();
  if ($duplicate_check['total'] < 1) {
    $db->query("UPDATE products_to_categories SET categories_id = " . (int)$new_parent_id . " WHERE products_id = " . (int)$products_id . " AND categories_id = " . (int)$current_category_id);
  }

  return $Admin->link('catalog.php', ['cPath' => $new_parent_id, 'pID' => $products_id]);
