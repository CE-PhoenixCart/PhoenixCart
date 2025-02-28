<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $products_sql = "SELECT p.products_id, pd.products_name, pd.products_viewed, l.name FROM products p, products_description pd, languages l WHERE p.products_id = pd.products_id AND l.languages_id = pd.language_id ORDER BY pd.products_viewed DESC";

  $link = $Admin->link('catalog.php', ['action' => 'new_product_preview', 'read' => 'only', 'origin' => 'stats_products_viewed.php?page=' . (int)($_GET['page'] ?? 1)]);
   
  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_NUMBER,
        'class' => 'col-1',
        'function' => function ($row) {
          static $count = 0;
          return str_pad(++$count, 2, '0', STR_PAD_LEFT) . '.';
        },
      ],
      [
        'name' => TABLE_HEADING_PRODUCTS,
        'function' => function ($row) {
          return '<a target="_blank" class="position-relative stretched-link" href="' . $row['onclick'] . '">' . $row['products_name'] . '</a>';
        },
      ],
      [
        'name' => TABLE_HEADING_VIEWED,
        'class' => 'text-end',
        'function' => function ($row) {
          return $row['products_viewed'];
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_PRODUCTS,
    'page' => $_GET['page'] ?? null,
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $products_sql,
    'width' => 12,
  ];
  
  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use ($link, &$table_definition) {
    $row['onclick'] = $link->set_parameter('pID', (int)$row['products_id']);
    $row['css'] = '';
  };
  
  $table_definition['split']->display_table();
  