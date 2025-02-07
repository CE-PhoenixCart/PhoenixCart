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
        'name' => TABLE_HEADING_LANGUAGE_NAME,
        'is_heading' => true,
        'function' => function ($row) {
          return $row['name']
               . ((DEFAULT_LANGUAGE == $row['code'])
                ? ' (' . TEXT_DEFAULT . ')'
                : '');
        },
      ],
      [
        'name' => TABLE_HEADING_LANGUAGE_CODE,
        'function' => function ($row) {
          return $row['code'];
        },
      ],
      [
        'name' => TABLE_HEADING_LANGUAGE_IMAGE,
        'function' => function ($row) use ($Admin) {
          return $Admin->catalog_image("includes/languages/{$row['directory']}/images/{$row['image']}");
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->languages_id) && ($row['languages_id'] == $row['info']->languages_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_LANGUAGES,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'lID',
    'db_id' => 'languages_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM languages ORDER BY sort_order",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $GLOBALS['link']->set_parameter('lID', (int)$row['languages_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['lID']) || ($_GET['lID'] == $row['languages_id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
      $row['onclick'] = (clone $GLOBALS['link'])->set_parameter('action', 'edit');
    } else {
      $row['css'] = '';
      $row['onclick'] = $GLOBALS['link'];
    }
  };

  $table_definition['split'] = new Paginator($table_definition);
  
  $table_definition['split']->display_table();
  