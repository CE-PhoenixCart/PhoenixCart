<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['send'];
  require 'includes/application_top.php';
  
  $get_addons_link = '';
  $get_addons_link .= '<div class="btn-group" role="group">';
    $get_addons_link .= '<button type="button" class="btn btn-dark mr-2 dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
      $get_addons_link .= GET_ADDONS;
    $get_addons_link .= '</button>';
    $get_addons_link .= '<div class="dropdown-menu">';
    foreach (GET_ADDONS_LINKS as $k => $v) {
      $get_addons_link .= '<a class="dropdown-item" target="_blank" href="' . $v . '">' . $k . '</a>';
    }
    $get_addons_link .= '</div>';
  $get_addons_link .= '</div>';

  $link = $Admin->link()->retain_query_except(['oID', 'action']);
  require 'includes/segments/process_action.php';

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_SEND_AT,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['send_at'];
        },
      ],
      [
        'name' => TABLE_HEADING_READY_TO_SEND,
        'class' => 'text-center',
        'is_heading' => false,
        'function' => function ($row) {
          return ($row['send_at'] < date('Y-m-d h:i:s') )
               ? '<i class="fas fa-circle-check text-success"></i>'
               : '<i class="fas fa-circle-xmark text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_NAME,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['fname'];
        },
      ],
      [
        'name' => TABLE_HEADING_EMAIL,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['email_address'];
        },
      ],
      [
        'name' => TABLE_HEADING_SLUG,
        'is_heading' => false,
        'function' => function ($row) {
          return $row['slug'];
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) {
          return (isset($row['info']->id) && ($row['id'] == $row['info']->id) )
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_OUTGOING,
    'page' => $_GET['page'] ?? null,
    'web_id' => 'oID',
    'db_id' => 'id',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
    'sql' => "SELECT * FROM outgoing ORDER BY send_at",
  ];

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $row['onclick'] = $GLOBALS['link']->set_parameter(
      'oID', $row['id']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['oID']) || ($_GET['oID'] == $row['id']))
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
      <h1 class="display-4 mb-2"><?= HEADING_TITLE; ?></h1>
    </div>
    <div class="col text-right align-self-center">
      <?=
      $get_addons_link,
      $Admin->button(GET_HELP, '', 'btn-dark mr-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? $Admin->button(BUTTON_SEND_READY_EMAILS, 'fas fa-paper-plane', 'btn-success mr-2', $Admin->link('outgoing.php', ['action' => 'send'])) . $Admin->button(BUTTON_INSERT_NEW_OUTGOING, 'fas fa-id-card', 'btn-danger', $Admin->link('outgoing.php', ['action' => 'new']))
      : $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $link)
      ?>
    </div>
  </div>

<?php
  $table_definition['split']->display_table();

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
