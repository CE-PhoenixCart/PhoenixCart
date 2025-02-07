<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_ONLINE,
        'function' => function (&$row) {
          return gmdate('H:i:s', time() - $row['time_entry']);
        },
      ],
      [
        'name' => TABLE_HEADING_CUSTOMER_ID,
        'function' => function (&$row) {
          return $row['customer_id'];
        },
      ],
      [
        'name' => TABLE_HEADING_FULL_NAME,
        'function' => function (&$row) {
          return $row['full_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_IP_ADDRESS,
        'function' => function (&$row) {
          return $row['ip_address'];
        },
      ],
      [
        'name' => TABLE_HEADING_ENTRY_TIME,
        'class' => 'text-end',
        'function' => function ($row) {
          return date('H:i:s', $row['time_entry']);
        },
      ],
      [
        'name' => TABLE_HEADING_LAST_CLICK,
        'class' => 'text-end',
        'function' => function (&$row) {
          return date('H:i:s', $row['time_last_click']);
        },
      ],
      [
        'name' => TABLE_HEADING_LAST_PAGE_URL,
        'class' => 'text-end',
        'function' => function (&$row) {
          return preg_replace('{ceid=[A-Z0-9,-]+[&]*}i', '', $row['last_page_url']);
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->session_id) && ($row['session_id'] == $row['info']->session_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_CUSTOMERS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'info',
    'db_id' => 'session_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM whos_online",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['Admin']->link();
    $row['onclick']->retain_query_except(['action'])->set_parameter(
      'info', $row['session_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['info']) || ($_GET['info'] == $row['session_id'])))
    {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split'] = new Paginator($table_definition);
  
  $table_definition['split']->display_table();
  