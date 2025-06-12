<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $keywords = null; $search_sql = '';
  if (!Text::is_empty($_GET['search'] ?? '')) {
    $keywords = Text::input($_GET['search']);
    
    $search_sql = "WHERE manufacturers_name LIKE '%" . $keywords. "%'";
    
    $admin_hooks->set('brandsListButtons', 'reset_keywords', function () {
      return $GLOBALS['Admin']->button(IMAGE_RESET, 'fas fa-angle-left', 'btn-light', $GLOBALS['Admin']->link('manufacturers.php'));
    });
  }
  
  $manufacturers_sql = "SELECT * FROM manufacturers $search_sql ORDER BY manufacturers_name";

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_MANUFACTURERS,
        'is_heading' => true,
        'function' => function ($row) {
          return $row['manufacturers_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return (isset($row['info']->manufacturers_id) && ($row['manufacturers_id'] == $row['info']->manufacturers_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS,
    'hooks' => [
      'button' => 'brandsListButtons',
    ],
    'page' => $_GET['page'] ?? null,
    'web_id' => 'mID',
    'db_id' => 'manufacturers_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => $manufacturers_sql,
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'mID', $row['manufacturers_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['mID']) || ($_GET['mID'] == $row['manufacturers_id']))
      && !Text::is_prefixed_by($GLOBALS['action'], 'new'))
    {
      $row = array_merge($row, $GLOBALS['db']->query("SELECT COUNT(*) AS products_count FROM products WHERE manufacturers_id = " . (int)$row['manufacturers_id'])->fetch_assoc());

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
  