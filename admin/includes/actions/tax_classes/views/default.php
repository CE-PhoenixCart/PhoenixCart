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
        'name' => TABLE_HEADING_TAX_CLASSES,
        'function' => function ($row) {
          return $row['tax_class_title'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->tax_class_id) && ($row['tax_class_id'] == $row['info']->tax_class_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $GLOBALS['link']->set_parameter('tID', $row['tax_class_id']) . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'tID',
    'db_id' => 'tax_class_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM tax_class ORDER BY tax_class_title",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['Admin']->link();
    $row['onclick']->retain_query_except(['action'])->set_parameter(
      'tID', $row['tax_class_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['tID']) || ($_GET['tID'] == $row['tax_class_id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
      $row['onclick']->set_parameter('action', 'edit');
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split'] = new Paginator($table_definition);

  $table_definition['split']->display_table();
