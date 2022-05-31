<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require 'includes/segments/process_action.php';

  Guarantor::ensure_global('currencies');
  $link = $Admin->link('stats_customers.php', ['action' => 'docsv', 'formid' => $_SESSION['sessiontoken']]);

  $db_tables = $customer_data->build_db_tables(['id', 'name'], 'customers');

  $customers_sql = "SELECT " . customer_query::build_columns($db_tables);
  $customers_sql .= "o.customers_id, SUM(op.products_quantity * op.final_price) AS ordersum FROM " . customer_query::build_joins($db_tables, []);
  $customers_sql .= ", orders_products op, orders o WHERE " . customer_query::TABLE_ALIASES['customers'];
  $customers_sql .= ".customers_id = o.customers_id AND o.orders_id = op.orders_id GROUP BY o.customers_id ORDER BY ordersum DESC";

  $row_number = MAX_DISPLAY_SEARCH_RESULTS * ((int)($_GET['page'] ?? 1) - 1);

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_NUMBER,
        'function' => function ($row) use (&$row_number) {
          return ++$row_number;
        },
      ],
      [
        'name' => TABLE_HEADING_CUSTOMERS,
        'function' => function ($row) use ($customer_data) {
          return $customer_data->get('name', $row);
        },
      ],
      [
        'name' => TABLE_HEADING_TOTAL_PURCHASED,
        'class' => 'text-right',
        'function' => function ($row) use ($currencies) {
          return $currencies->format($row['ordersum']);
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) use ($Admin) {
          return '<a class="text-dark" href="' . $GLOBALS['link']->set_parameter('cID', $row['customers_id']) . '"><i class="fas fa-file-csv mr-2"></i></a>'
               . '<a class="text-dark" href="' . $Admin->link('orders.php', ['cID' => $row['customers_id']]) . '"><i class="fas fa-eye"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_CUSTOMERS,
    'page' => $_GET['page'] ?? null,
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $customers_sql,
    'row_count' => $db->query("SELECT COUNT(DISTINCT customers_id) AS row_count FROM orders")->fetch_assoc()['row_count'],
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) {
    $row['css'] = '';
  };

  require 'includes/template_top.php';
?>

  <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>

<?php
  $table_definition['split']->display_table();

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
