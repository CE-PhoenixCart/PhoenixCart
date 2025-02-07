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
  
  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action)
      ? new Button(IMAGE_INSERT_NEW_ADMIN, 'fas fa-users', 'btn-danger', [], $Admin->link('administrators.php', ['action' => 'new']))
      : new Button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', [], $Admin->link('administrators.php'))
      ?>
    </div>
  </div>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }
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
