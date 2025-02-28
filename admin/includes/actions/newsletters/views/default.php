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
        'name' => TABLE_HEADING_NEWSLETTERS,
        'is_heading' => true,
        'function' => function ($row) {
          return $row['title'];
        },
      ],
      [
        'name' => TABLE_HEADING_MODULE,
        'function' => function ($row) {
          return $row['module'];
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_ADDED,
        'function' => function ($row) {
          return Date::abridge($row['date_added']);
        },
      ],
      [
        'name' => TABLE_HEADING_SIZE,
        'function' => function ($row) {
          return number_format($row['content_length']) . ' bytes';
        },
      ],
      [
        'name' => TABLE_HEADING_SENT,
        'class' => 'text-center',
        'function' => function ($row) {
          return ($row['status'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i>'
               : '<i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_STATUS,
        'class' => 'text-center',
        'function' => function (&$row) {
          return ($row['locked'] > 0)
               ? '<i class="fas fa-lock text-success"></i>'
               : '<i class="fas fa-lock-open text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->orders_id))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'nID',
    'db_id' => 'newsletters_id',
    'sql' => "SELECT *, LENGTH(content) AS content_length FROM newsletters ORDER BY date_added DESC",
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use ($link, &$table_definition) {
    $link->set_parameter('nID', $row['newsletters_id']);
    if (!isset($table_definition['info']) && (!isset($_GET['nID']) || ($_GET['nID'] == $row['newsletters_id']))) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = (clone $link)->set_parameter('action', 'preview');
      $row['css'] = ' class="table-active"';
    } else {
      $row['onclick'] = clone $link;
      $row['css'] = '';
    }
  };

  $table_definition['split']->display_table();
?>
