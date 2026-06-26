<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $filter = [];

  if (isset($_GET['module'])) {
    if (in_array($_GET['module'], $modules)) {
      $filter[] = "module = '" . $db->escape($_GET['module']) . "'";
    } else {
      unset($_GET['module']);
    }
  }

  if (!empty($_GET['search'])) {
    $filter[] = "identifier LIKE '%" . $db->escape($_GET['search']) . "%'";
  }

  $action_recorder_sql = "SELECT * FROM action_recorder";
  if (count($filter) > 0) {
    $action_recorder_sql .= " WHERE " . implode(" AND ", $filter);
    
    $admin_hooks->set('actionRecorderButtons', 'reset_search', function () {
      return $GLOBALS['Admin']->button(IMAGE_RESET, 'fas fa-angle-left', 'btn-light', $GLOBALS['Admin']->link('action_recorder.php'));
    });
  }
  $action_recorder_sql .= " ORDER BY date_added DESC";

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_MODULE,
        'function' => function (&$row) {
          return $row['module'];
        },
      ],
      [
        'name' => TABLE_HEADING_CUSTOMER,
        'function' => function (&$row) {
          return htmlspecialchars($row['user_name']) . ' [' . (int)$row['user_id'] . ']';
        },
      ],
      [
        'name' => TABLE_HEADING_IDENTIFIER,
        'function' => function (&$row) {
          return $row['identifier'] ?? '';
        },
      ],
      [
        'name' => TABLE_HEADING_SUCCESS,
        'class' => 'text-center',
        'function' => function (&$row) {
          return ($row['success'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i>'
               : '<i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_ADDED,
        'class' => 'text-end',
        'function' => function (&$row) {
          return $GLOBALS['date_time_formatter']->format((new Date($row['date_added']))->get_timestamp());
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ENTRIES,
    'hooks' => [
      'button' => 'actionRecorderButtons',
    ],
    'page' => $_GET['page'] ?? null,
    'sql' => $action_recorder_sql,
    'width' => 12,
  ];
  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use (&$table_definition) {
    if (isset($GLOBALS[$row['module']]->title)) {
      $row['module'] = $GLOBALS[$row['module']]->title;
    }

    $link = $GLOBALS['Admin']->link()->retain_query_except(['action'])->set_parameter('aID', $row['id']);
    if (!isset($table_definition['info']) && (!isset($_GET['aID']) || ($_GET['aID'] === $row['id']))) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
    } else {
      $row['css'] = '';
    }
  };

  $table_definition['split']->display_table();
