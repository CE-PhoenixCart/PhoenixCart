<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_POST['categories_id'])) {
    $categories_id = Text::input($_POST['categories_id']);

    $category_tree = new category_tree();
    $descendants = array_reverse($category_tree->get_descendants($categories_id));
    $descendants[] = $categories_id;

    $products_delete = array_column($db->fetch_all(sprintf(<<<'EOSQL'
SELECT c1.products_id
 FROM products_to_categories c1 LEFT JOIN products_to_categories c2
   ON c1.products_id = c2.products_id AND c1.categories_id != c2.categories_id
 WHERE c1.categories_id IN (%s) AND c2.categories_id IS NULL
EOSQL
      , implode(', ', array_map('intval', $descendants)))), 'products_id');

// removing categories can be a lengthy process
    System::set_time_limit(0);
    array_filter($products_delete, 'Products::remove');
    array_filter($descendants, 'Categories::remove');
  }

  return $Admin->link('catalog.php', ['cPath' => $cPath]);
