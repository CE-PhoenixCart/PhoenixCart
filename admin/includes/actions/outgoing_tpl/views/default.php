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
        'name' => TABLE_HEADING_SLUG,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['slug'];
        },
      ],
      [
        'name' => TABLE_HEADING_TITLE,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['title'];
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_ADDED,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['date_added'];
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
    'sql' => "SELECT * FROM outgoing_tpl ORDER BY slug",
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
  