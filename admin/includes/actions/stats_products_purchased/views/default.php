<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $products_sql = sprintf(<<<'EOSQL'
SELECT p.products_id, p.products_ordered, pd.products_name
 FROM products p INNER JOIN products_description pd ON pd.products_id = p.products_id
 WHERE pd.language_id = %d AND p.products_ordered > 0
 GROUP BY pd.products_id
 ORDER BY p.products_ordered DESC, pd.products_name
EOSQL
    , (int)$_SESSION['languages_id']);

  $link = $Admin->link('catalog.php');
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
          return '<a target="_blank" class="stretched-link" href="' . $row['link'] . '">' . $row['products_name'] . '</a>';
        },
      ],
      [
        'name' => TABLE_HEADING_PURCHASED,
        'class' => 'text-end',
        'function' => function ($row) {
          return $row['products_ordered'];
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
    $link->set_parameter('search', $row['products_name']);

    $row['link'] = $link;
    $row['css'] = ' style="transform: rotate(0);"';
  };
  
  $table_definition['split']->display_table();
  