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
        'name' => TABLE_HEADING_ADMINISTRATORS,
        'function' => function ($row) {
          return $row['user_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_HTPASSWD,
        'class' => 'text-center',
        'function' => function ($row) use ($apache_users, $is_iis) {
          if ($is_iis) {
            $htpasswd_secured = TEXT_HTPASSWRD_NA_IIS;
          } elseif (in_array($row['user_name'], $apache_users)) {
            $htpasswd_secured = '<i class="fas fa-check-circle text-success"></i>';
          } else {
            $htpasswd_secured = '<i class="fas fa-times-circle text-danger"></i>';
          }
          return $htpasswd_secured;
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
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ENTRIES,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'aID',
    'db_id' => 'id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => 'SELECT id, user_name FROM administrators ORDER BY user_name',
  ];
  
  $table_definition['split'] = new Paginator($table_definition);
  $link = $Admin->link()->retain_query_except(['action']);
  $table_definition['function'] = function (&$row) use ($link, $action, &$table_definition) {
    $link->set_parameter('aID', $row['id']);
    if (!isset($table_definition['info']) && (!isset($_GET['aID']) || ($_GET['aID'] == $row['id'])) && (substr($action, 0, 3) !== 'new')) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = (clone $link)->set_parameter('action', 'edit');
      $row['css'] = ' class="table-active"';
      $row['info']->link = $link;
    } else {
      $row['onclick'] = $link;
      $row['css'] = '';
    }
  };

  $table_definition['split']->display_table();  