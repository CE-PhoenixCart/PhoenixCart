<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $link = $Admin->link('stats_customers.php', ['action' => 'docsv', 'formid' => $_SESSION['sessiontoken']]);

  $db_tables = $customer_data->build_db_tables(['id', 'name'], 'customers');

  $customers_sql = "SELECT " . customer_query::build_columns($db_tables);
  $customers_sql .= "o.customers_id, SUM(op.products_quantity * op.final_price) AS ordersum FROM " . customer_query::build_joins($db_tables, []);
  $customers_sql .= ", orders_products op, orders o WHERE " . customer_query::determine_alias('customers');
  $customers_sql .= ".customers_id = o.customers_id AND o.orders_id = op.orders_id GROUP BY o.customers_id ORDER BY ordersum DESC";

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
        'name' => TABLE_HEADING_CUSTOMERS_ID,
        'class' => 'col-1',
        'function' => function ($row) use ($customer_data) {
          return $customer_data->get('id', $row);
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
        'class' => 'text-end',
        'function' => function ($row) use ($currencies) {
          return $currencies->format($row['ordersum']);
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) use ($Admin) {
          return '<a class="text-dark" href="' . $GLOBALS['link']->set_parameter('cID', $row['customers_id']) . '"><i class="fas fa-file-csv me-2"></i></a>'
               . '<a class="text-dark" href="' . $Admin->link('orders.php', ['cID' => $row['customers_id']]) . '"><i class="fas fa-eye"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_CUSTOMERS,
    'page' => $_GET['page'] ?? null,
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $customers_sql,
    'row_count' => $db->query("SELECT COUNT(DISTINCT customers_id) AS row_count FROM orders")->fetch_assoc()['row_count'],
    'width' => 12,
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) {
    $row['css'] = '';
  };

  $table_definition['split']->display_table();
