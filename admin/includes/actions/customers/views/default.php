<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $customers_sql = $customer_data->build_read([
        'id',
        'sortable_name',
        'email_address',
        'country_id',
        'date_account_created',
        'date_account_last_modified',
        'date_last_logon',
        'telephone',
      ],
      'customers');
      
  $keywords = null;
  if (!Text::is_empty($_GET['search'] ?? '')) {
    $keywords = Text::input($_GET['search']);
    $customers_sql = $customer_data->add_search_criteria($customers_sql, $keywords);
    $admin_hooks->set('customersListButtons', 'reset_keywords', function () {
      return $GLOBALS['Admin']->button(IMAGE_RESET, 'fas fa-angle-left', 'btn-light', $GLOBALS['Admin']->link('customers.php'));
    });
  }
  
  $customers_sql = $customer_data->add_order_by($customers_sql, ['id' => 'DESC']);

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_ID,
        'class' => 'col-1',
        'function' => function (&$row) use ($customer_data) {
            return $customer_data->get('id', $row);
          },
      ],
      [
        'name' => TABLE_HEADING_NAME,
        'function' => function (&$row) use ($customer_data) {
            return $customer_data->get('sortable_name', $row);
          },
      ],
      [
        'name' => TABLE_HEADING_ACCOUNT_CREATED,
        'class' => 'text-end',
        'function' => function (&$row) use ($customer_data) {
          return Date::abridge($customer_data->get('date_account_created', $row));
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) use ($customer_data) {
          return (isset($row['info']->id) && ($row['info']->id === $customer_data->get('id', $row)))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_CUSTOMERS,
    'hooks' => [
      'button' => 'customersListButtons',
    ],
    'page' => $_GET['page'] ?? null,
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $customers_sql,
  ];
    
  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use ($customer_data, &$table_definition) {
    $link = $GLOBALS['Admin']->link('customers.php')->retain_query_except(['action'])->set_parameter('cID', $customer_data->get('id', $row));
    if (!isset($table_definition['info']) && (!isset($_GET['cID']) || ($_GET['cID'] === $customer_data->get('id', $row)))) {
      $reviews_query = $GLOBALS['db']->query("SELECT COUNT(*) AS number_of_reviews FROM reviews WHERE customers_id = " . (int)$customer_data->get('id', $row));
      $reviews = $reviews_query->fetch_assoc();
      $row['number_of_reviews'] = $reviews['number_of_reviews'];
      
      $customer_data->get([
        'sortable_name',
        'name',
        'email_address',
        'country_name',
        'id',
        'number_of_logons',
        'date_last_logon',
        'date_account_last_modified',
        'date_account_created',
      ], $row);
      
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = $link->set_parameter('action', 'edit');
      $row['css'] = ' class="table-active"';
    } else {
      $row['onclick'] = $link;
      $row['css'] = '';
    }
  };

  $table_definition['split']->display_table();
