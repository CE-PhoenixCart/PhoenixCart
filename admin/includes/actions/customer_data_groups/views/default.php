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
        'name' => TABLE_HEADING_CUSTOMER_DATA_GROUP_NAME,
        'is_heading' => true,
        'function' => function ($row) {
          return $row['customer_data_groups_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_SORT_ORDER,
        'function' => function ($row) {
          return $row['cdg_vertical_sort_order'];
        },
      ],
      [
        'name' => TABLE_HEADING_WIDTH,
        'function' => function ($row) {
          return $row['customer_data_groups_width'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->customer_data_groups_id) && ($row['customer_data_groups_id'] == $row['info']->customer_data_groups_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_CUSTOMER_DATA_GROUPS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'cdgID',
    'db_id' => 'customer_data_groups_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => sprintf(<<<'EOSQL'
SELECT *
 FROM customer_data_groups
 WHERE language_id = %d
 ORDER BY cdg_vertical_sort_order
EOSQL
      , (int)$_SESSION['languages_id']),
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'cdgID', $row['customer_data_groups_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['cdgID']) || ($_GET['cdgID'] == $row['customer_data_groups_id']))
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
  