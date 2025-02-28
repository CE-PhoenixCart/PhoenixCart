<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $products_sql = sprintf(<<<'EOSQL'
SELECT pd.products_id, pd.products_name, p.products_date_available
 FROM products_description pd, products p
 WHERE p.products_id = pd.products_id AND p.products_date_available IS NOT NULL AND pd.language_id = %d
 ORDER BY p.products_date_available
EOSQL
    , (int)$_SESSION['languages_id']);

  $action = '';
  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_PRODUCTS,
        'function' => function (&$row) {
            return $row['products_name'];
          },
      ],
      [
        'name' => TABLE_HEADING_DATE_EXPECTED,
        'class' => 'text-end',
        'function' => function (&$row) {
          return Date::abridge($row['products_date_available']);
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->products_id))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED,
    'page' => $_GET['page'] ?? null,
    'sql' => $products_sql,
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $link = $Admin->link('products_expected.php')->retain_query_except(['action']);
  $table_definition['function'] = function (&$row) use ($link, &$table_definition) {
    if (!isset($table_definition['info']) && (!isset($_GET['pID']) || ($_GET['pID'] == $row['products_id']))) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = $GLOBALS['Admin']->link('catalog.php', ['pID' => (int)$row['products_id'], 'action' => 'new_product']);
      $row['css'] = ' class="table-active"';
    } else {
      $row['onclick'] = (clone $link)->set_parameter('pID', $row['products_id']);
      $row['css'] = '';
    }
  };
  
  $table_definition['split']->display_table();
  