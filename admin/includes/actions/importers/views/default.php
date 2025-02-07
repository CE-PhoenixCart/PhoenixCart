<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_IMPORTERS,
        'is_heading' => true,
        'function' => function ($row) {
          return $row['importers_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->importers_id) && ($row['importers_id'] == $row['info']->importers_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_IMPORTERS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'iID',
    'db_id' => 'importers_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM importers ORDER BY importers_name",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'iID', $row['importers_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['iID']) || ($_GET['iID'] == $row['importers_id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $row = array_merge($row, $GLOBALS['db']->query("SELECT COUNT(*) AS products_count FROM products WHERE importers_id = " . (int)$row['importers_id'])->fetch_assoc());

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
  