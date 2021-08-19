<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require 'includes/template_top.php';

  $products_sql = sprintf(<<<'EOSQL'
SELECT p.products_id, p.products_ordered, pd.products_name
 FROM products p INNER JOIN products_description pd ON pd.products_id = p.products_id
 WHERE pd.language_id = %d AND p.products_ordered > 0
 GROUP BY pd.products_id
 ORDER BY p.products_ordered DESC, pd.products_name
EOSQL
    , (int)$_SESSION['languages_id']);

  $link = $Admin->link('catalog.php', ['action' => 'new_product_preview', 'read' => 'only', 'origin' => 'stats_products_purchased.php?page=' . (int)($_GET['page'] ?? 1)]);
  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_NUMBER,
        'function' => function ($row) {
          static $count = 0;
          return str_pad(++$count, 2, '0', STR_PAD_LEFT) . '.';
        },
      ],
      [
        'name' => TABLE_HEADING_PRODUCTS,
        'function' => function ($row) {
          return '<a href="' . $row['onclick'] . '">' . $row['products_name'] . '</a>';
        },
      ],
      [
        'name' => TABLE_HEADING_PURCHASED,
        'class' => 'text-right',
        'function' => function ($row) {
          return $row['products_ordered'];
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_PRODUCTS,
    'page' => $_GET['page'] ?? null,
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $products_sql,
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use ($link, &$table_definition) {
    $row['onclick'] = $link->set_parameter('pID', (int)$row['products_id']);
    $row['css'] = '';
  };
?>

  <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>

<?php
  $table_definition['split']->display_table();

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
