<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $subcategory_products_check = $db->query(sprintf(<<<'EOSQL'
SELECT COUNT(*) AS total
 FROM (
   SELECT categories_id AS id FROM categories WHERE parent_id = %1$d
  UNION
   SELECT p2c.products_id AS id
    FROM products_to_categories p2c LEFT JOIN products_to_categories self ON p2c.products_id = self.products_id AND p2c.categories_id != self.categories_id
    WHERE p2c.categories_id = %1$d AND self.categories_id IS NULL
 ) combined
EOSQL
    , (int)$_GET['cID']))->fetch_assoc();

  $heading = TEXT_INFO_HEADING_DELETE_CATEGORY;

  $contents = ['form' => (new Form('categories', $Admin->link('catalog.php', ['action' => 'delete_category_confirm', 'cPath' => $cPath])))->hide('categories_id', $cInfo->categories_id)];
  $contents[] = ['text' => TEXT_DELETE_CATEGORY_INTRO];
  $contents[] = ['text' => '<strong>' . $cInfo->categories_name . '</strong>'];
  if ($subcategory_products_check['total'] > 0) {
    $contents[] = ['text' => TEXT_DELETE_WARNING];
  }
  
  $contents[] = [
    'class' => 'd-grid',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger btn-lg mb-1'),
  ];
  
  $contents[] = [
    'class' => 'text-center',
    'text' => $Admin->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $Admin->link('catalog.php', ['cPath' => $cPath, 'cID' => $cInfo->categories_id])),
  ];
