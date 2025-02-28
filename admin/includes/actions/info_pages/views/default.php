<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $required_slugs = info_pages::requirements();

  if ( count($required_slugs) > 0 )   {
    echo '<div class="alert alert-danger">',
         sprintf(MISSING_SLUGS_ERROR, implode(', ', $required_slugs)),
         '</div>';
  }

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_PAGE_ID,
        'function' => function ($row) {
          return $row['pages_id'];
        },
      ],
      [
        'name' => TABLE_HEADING_PAGE_TITLE,
        'function' => function ($row) {
          return $row['pages_title'];
        },
      ],
      [
        'name' => TABLE_HEADING_SLUG,
        'function' => function ($row) {
          return $row['slug'];
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_ADDED,
        'function' => function ($row) {
          return Date::abridge($row['date_added']);
        },
      ],
      [
        'name' => TABLE_HEADING_SORT_ORDER,
        'class' => 'text-center',
        'function' => function ($row) {
          return $row['sort_order'];
        },
      ],
      [
        'name' => TABLE_HEADING_STATUS,
        'class' => 'text-center',
        'function' => function ($row) {
          $flag_link = (clone $row['link'])->set_parameter('action', 'set_flag')->set_parameter('formid', $_SESSION['sessiontoken']);
          return ($row['pages_status'] == '1')
                ? '<i class="fas fa-check-circle text-success"></i> <a href="' . $flag_link->set_parameter('flag', '0') . '"><i class="fas fa-times-circle text-muted"></i></a>'
                : '<a href="' . $flag_link->set_parameter('flag', '1') . '"><i class="fas fa-check-circle text-muted"></i></a> <i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return isset($row['info']->pages_id)
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_PAGES,
    'page' => $_GET['page'] ?? null,
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => sprintf(<<<'EOSQL'
SELECT *
 FROM pages p LEFT JOIN pages_description pd ON p.pages_id = pd.pages_id
 WHERE pd.languages_id = %d
 ORDER BY %s
EOSQL
      , (int)$_SESSION['languages_id'], $admin_hooks->cat('order_by') ?? 'p.sort_order'),
  ];

  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['link'] = $GLOBALS['link']->set_parameter('pID', $row['pages_id']);
    if (!isset($table_definition['info']) && (!isset($_GET['pID']) || ($_GET['pID'] === $row['pages_id']))) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = (clone $row['link'])->set_parameter('action', 'edit');
      $row['css'] = ' class="table-active"';
    } else {
      $row['onclick'] = $row['link'];
      $row['css'] = '';
    }
  };

  $table_definition['split']->display_table();
?>
