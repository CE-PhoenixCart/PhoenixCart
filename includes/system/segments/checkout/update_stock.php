<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (STOCK_LIMITED === 'true') {
// Stock Update - Joao Correia
    if (DOWNLOAD_ENABLED === 'true') {
      $stock_sql = <<<'EOSQL'
SELECT p.products_quantity, IF(pad.products_attributes_filename IS NULL, 0, 1) AS is_virtual
 FROM products p
   LEFT JOIN products_attributes pa ON p.products_id=pa.products_id
   LEFT JOIN products_attributes_download pad ON pa.products_attributes_id=pad.products_attributes_id
 WHERE p.products_id = %d
 ORDER BY pad.products_attributes_filename DESC
 LIMIT 1
EOSQL;
    } else {
      $stock_sql = "SELECT products_quantity FROM products WHERE products_id = %d";
    }

    foreach ($GLOBALS['order']->products as $product) {
      $product_id = Product::build_prid($product['id']);
      if (($stock_values = $GLOBALS['db']->query(sprintf($stock_sql, (int)$product_id))->fetch_assoc()) && empty($stock_values['is_virtual'])) {
        $stock_left = $stock_values['products_quantity'] - $product['qty'];

        $sql_data = ['products_quantity' => (int)$stock_left];
        if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT === 'false') ) {
          $sql_data['products_status'] = '0';
        }

        $GLOBALS['db']->perform('products', $sql_data, 'update', 'products_id = ' . (int)$product_id);
      }
    }
  }
