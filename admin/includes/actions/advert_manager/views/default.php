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
        'name' => TABLE_HEADING_ADVERT,
        'function' => function ($row) {
          return $row['advert_title'];
        },
      ],
      [
        'name' => TABLE_HEADING_GROUP,
        'function' => function ($row) {
          return $row['advert_group'];
        },
      ],
      [
        'name' => TABLE_HEADING_SORT_ORDER,
        'function' => function ($row) {
          return $row['sort_order'] ?? 0;
        },
      ],
      [
        'name' => TABLE_HEADING_STATUS,
        'class' => 'text-end',
        'function' => function ($row) {
          $flag_link = (clone $row['onclick'])->set_parameter('action', 'set_flag')->set_parameter('formid', $_SESSION['sessiontoken']);
          return ($row['status'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i> <a href="' . $flag_link->set_parameter('flag', '0') . '"><i class="fas fa-times-circle text-muted"></i></a>'
               : '<a href="' . $flag_link->set_parameter('flag', '1') . '"><i class="fas fa-check-circle text-muted"></i></a>  <i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->advert_id) && ($row['advert_id'] == $row['info']->advert_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ADVERTS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'aID',
    'db_id' => 'advert_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM advert ORDER BY advert_group, sort_order",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = (clone $GLOBALS['link'])->set_parameter('aID', $row['advert_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['aID']) || ($_GET['aID'] == $row['advert_id'])))
    {
      $row = array_merge($row, $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT *
 FROM advert_info
 WHERE advert_id = %d
 ORDER BY languages_id = %d DESC
 LIMIT 1
EOSQL
        , (int)$row['advert_id'], (int)$_SESSION['languages_id']))->fetch_assoc() ?? []);

      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick']->set_parameter('action', 'edit');
      $row['css'] = ' class="table-active"';
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['split']->display_table();

  if (isset($table_definition['info'])) {
    $tInfo = &$table_definition['info'];
  }
