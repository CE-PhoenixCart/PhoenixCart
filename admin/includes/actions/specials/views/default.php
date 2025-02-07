<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $specials_sql = sprintf(<<<'EOSQL'
SELECT p.*, pd.*, s.*
 FROM specials s
  INNER JOIN products p ON p.products_id = s.products_id
  INNER JOIN products_description pd ON p.products_id = pd.products_id AND pd.language_id = %d
 ORDER BY pd.products_name
EOSQL
      , (int)$_SESSION['languages_id']);

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_PRODUCTS,
        'function' => function (&$row) {
          return $row['products_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_PRODUCTS_PRICE,
        'function' => function (&$row) {
          return $GLOBALS['currencies']->format($row['products_price']);
        },
      ],
      [
        'name' => TABLE_HEADING_SPECIAL_PRICE,
        'function' => function (&$row) {
          return $GLOBALS['currencies']->format($row['specials_new_products_price']);
        },
      ],
      [
        'name' => TABLE_HEADING_STATUS,
        'class' => 'text-end',
        'function' => function (&$row) {
          $href = (clone $row['onclick'])->set_parameter('action', 'set_flag');
          return ($row['status'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i> <a href="' . $href->set_parameter('flag', '0')  . '"><i class="fas fa-times-circle text-muted"></i></a>'
               : '<a href="' . $href->set_parameter('flag', '1') . '"><i class="fas fa-check-circle text-muted"></i></a> <i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_SPECIALS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'sID',
    'db_id' => 'specials_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $specials_sql,
  ];


  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['split']->display_table();

  if (isset($table_definition['info'])) {
    $sInfo = &$table_definition['info'];
  }
