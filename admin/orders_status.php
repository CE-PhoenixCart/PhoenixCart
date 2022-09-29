<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['delete'];
  require 'includes/application_top.php';
  $link = $Admin->link()->retain_query_except(['action', 'oID']);

  require 'includes/segments/process_action.php';

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_ORDERS_STATUS,
        'is_heading' => true,
        'function' => function ($row) {
          return (DEFAULT_ORDERS_STATUS_ID == $row['orders_status_id'])
               ? $row['orders_status_name'] . ' (' . TEXT_DEFAULT . ')'
               : $row['orders_status_name'];
        },
      ],
      [
        'name' => TABLE_HEADING_PUBLIC_STATUS,
        'class' => 'text-center',
        'function' => function ($row) {
          return ($row['public_flag'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i>'
               : '<i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_DOWNLOADS_STATUS,
        'class' => 'text-center',
        'function' => function ($row) {
          return ($row['downloads_flag'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i>'
               : '<i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) {
          return (isset($row['info']->orders_status_id) && ($row['orders_status_id'] == $row['info']->orders_status_id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'oID',
    'db_id' => 'orders_status_id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM orders_status WHERE language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY orders_status_id",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'oID', $row['orders_status_id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['oID']) || ($_GET['oID'] == $row['orders_status_id']))
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

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
        empty($action)
      ? $Admin->button(IMAGE_INSERT, 'fas fa-plus', 'btn-danger', (clone $link)->set_parameter('action', 'new'))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $link)
      ?>
    </div>
  </div>

<?php
  $table_definition['split']->display_table();

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
