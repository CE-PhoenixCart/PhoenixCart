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
        'name' => TABLE_HEADING_CUSTOMER_ID,
        'function' => function ($row) {
          return (int)$row['customers_id'];
        },
      ],
      [
        'name' => TABLE_HEADING_CUSTOMER_NAME,
        'function' => function ($row) {
          return $row['customers_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_ADDED,
        'function' => function ($row) {
          return Date::abridge($row['date_added']);
        },
      ],
      [
        'name' => TABLE_HEADING_STATUS,
        'class' => 'text-end',
        'function' => function ($row) {
          $flag_link = (clone $row['onclick'])->set_parameter('action', 'set_flag')->set_parameter('formid', $_SESSION['sessiontoken']);
          return ($row['testimonials_status'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i> <a href="' . $flag_link->set_parameter('flag', '0') . '"><i class="fas fa-times-circle text-muted"></i></a>'
               : '<a href="' . $flag_link->set_parameter('flag', '1') . '"><i class="fas fa-check-circle text-muted"></i></a>  <i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->testimonials_id) && ($row['testimonials_id'] == $row['info']->testimonials_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_TESTIMONIALS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'tID',
    'db_id' => 'testimonials_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM testimonials ORDER BY testimonials_id DESC",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = (clone $GLOBALS['link'])->set_parameter('tID', $row['testimonials_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['tID']) || ($_GET['tID'] == $row['testimonials_id'])))
    {
      $row = array_merge($row, $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT *
 FROM testimonials_description
 WHERE testimonials_id = %d
 ORDER BY languages_id = %d DESC
 LIMIT 1
EOSQL
        , (int)$row['testimonials_id'], (int)$_SESSION['languages_id']))->fetch_assoc() ?? []);

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
?>
