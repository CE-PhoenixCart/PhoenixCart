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
        'name' => TABLE_HEADING_SEND_AT,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['send_at'];
        },
      ],
      [
        'name' => TABLE_HEADING_READY_TO_SEND,
        'class' => 'text-center',
        'is_heading' => false,
        'function' => function ($row) {
          return ($row['send_at'] < date('Y-m-d h:i:s') )
               ? '<i class="fas fa-circle-check text-success"></i>'
               : '<i class="fas fa-circle-xmark text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_NAME,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['fname'];
        },
      ],
      [
        'name' => TABLE_HEADING_EMAIL,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['email_address'];
        },
      ],
      [
        'name' => TABLE_HEADING_SLUG,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['slug'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->id) && ($row['id'] == $row['info']->id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_OUTGOING,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'oID',
    'db_id' => 'id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM outgoing ORDER BY send_at",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'oID', $row['id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['oID']) || ($_GET['oID'] == $row['id']))
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
  