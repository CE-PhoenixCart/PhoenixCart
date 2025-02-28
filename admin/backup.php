<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['download'];
  require 'includes/application_top.php';

  $link = $Admin->link()->retain_query_except(['action', 'file']);

  function phoenix_ensure_constant($name, $default) {
    if (!defined($name)) {
      define($name, $default);
    }
  }

// Used in the "Backup Manager" to compress backups
  phoenix_ensure_constant('LOCAL_EXE_GZIP', '/usr/bin/gzip');
  phoenix_ensure_constant('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
  phoenix_ensure_constant('LOCAL_EXE_ZIP', '/usr/bin/zip');
  phoenix_ensure_constant('LOCAL_EXE_UNZIP', '/usr/bin/unzip');

  require 'includes/segments/process_action.php';

// check if the backup directory exists
  $dir_ok = false;
  if (is_dir(DIR_FS_BACKUP)) {
    if (Path::is_writable(DIR_FS_BACKUP)) {
      $dir_ok = true;
    } else {
      $messageStack->add(ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    $messageStack->add(ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
  }

  $compressions = ['zip' => 'ZIP', '.gz' => 'GZIP'];
  
  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-6 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons')
      ?>
    </div>
  </div>

<?php
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
