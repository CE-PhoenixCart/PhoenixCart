<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_ORDERS_STATUS,
        'is_heading' => true,
        'function' => function ($row) {
          return (DEFAULT_ORDERS_STATUS_ID == $row['orders_status_id'])
               ? $row['orders_status_name'] . ' (' . TEXT_DEFAULT . ')'
               : $row['orders_status_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_PUBLIC_STATUS,
        'class' => 'text-center',
        'function' => function ($row) {
          return ($row['public_flag'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i>'
               : '<i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_DOWNLOADS_STATUS,
        'class' => 'text-center',
        'function' => function ($row) {
          return ($row['downloads_flag'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i>'
               : '<i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->orders_status_id) && ($row['orders_status_id'] == $row['info']->orders_status_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'oID',
    'db_id' => 'orders_status_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM orders_status WHERE language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY orders_status_id",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'oID', $row['orders_status_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['oID']) || ($_GET['oID'] == $row['orders_status_id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
      $row['onclick'] = (clone $row['onclick'])->set_parameter('action', 'edit');
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split'] = new Paginator($table_definition);

  $table_definition['split']->display_table();
