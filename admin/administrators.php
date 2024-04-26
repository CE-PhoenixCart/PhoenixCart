<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $is_iis = stripos($_SERVER['SERVER_SOFTWARE'], 'iis');
  Guarantor::ensure_global('Admin');

  $htaccess_path = DIR_FS_ADMIN . '.htaccess';
  $htpasswd_path = DIR_FS_ADMIN . '.htpasswd_phoenix';
  $authuserfile_lines = [
    '##### Phoenix ADMIN PROTECTION - BEGIN #####',
    'AuthType Basic',
    'AuthName "CE Phoenix Administration Tool"',
    "AuthUserFile $htpasswd_path",
    'Require valid-user',
    '##### Phoenix ADMIN PROTECTION - END #####',
  ];

  $htaccess_lines = [];
  if (!$is_iis && file_exists($htpasswd_path) && Path::is_writable($htpasswd_path) && file_exists($htaccess_path) && Path::is_writable($htaccess_path)) {
    if (filesize($htaccess_path) > 0) {
      $htaccess_lines = explode("\n", file_get_contents($htaccess_path));
    }

    $htpasswd_lines = (filesize($htpasswd_path) > 0) ? explode("\n", file_get_contents($htpasswd_path)) : [];
  } else {
    $htpasswd_lines = false;
  }

  require 'includes/segments/process_action.php';

  $secMessageStack = new messageStack();

  $apache_users = [];
  if (is_array($htpasswd_lines)) {
    if (empty($htpasswd_lines)) {
      $secMessageStack->add(sprintf(HTPASSWD_INFO, $htaccess_path, implode('<br>', $authuserfile_lines), $htpasswd_path), 'error');
    } else {
      $secMessageStack->add(HTPASSWD_SECURED, 'success');

      foreach ($htpasswd_lines as $htpasswd_line) {
        $end = strpos($htpasswd_line, ':');
        if (false !== $end) {
          $apache_users[] = substr($htpasswd_line, 0, $end);
        }
      }
    }
  } else if (!$is_iis) {
    $secMessageStack->add(sprintf(HTPASSWD_PERMISSIONS, $htaccess_path, $htpasswd_path), 'error');
  }
  
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
        'class' => 'text-right',
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

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-4 text-left text-lg-right align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark mr-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? new Button(IMAGE_INSERT_NEW_ADMIN, 'fas fa-users', 'btn-danger', [], $Admin->link('administrators.php', ['action' => 'new']))
      : new Button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', [], $Admin->link('administrators.php'))
      ?>
    </div>
  </div>

<?php
  $table_definition['split']->display_table();
?>

<div class="row mt-3">
  <div class="col-12 col-sm-8">
    <?= $secMessageStack->output() ?>
  </div>
</div>

<?php  
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
