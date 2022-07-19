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

  <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>

  <div class="row no-gutters">
    <div class="col-12 col-sm-8">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th><?= TABLE_HEADING_TITLE ?></th>
              <th><?= TABLE_HEADING_FILE_DATE ?></th>
              <th class="text-right"><?= TABLE_HEADING_FILE_SIZE ?></th>
              <th class="text-right"><?= TABLE_HEADING_ACTION ?></th>
            </tr>
          </thead>
          <tbody>

<?php
  $dir = dir(DIR_FS_BACKUP);
  $contents = [];
  while ($file = $dir->read()) {
    if (!is_dir(DIR_FS_BACKUP . $file) && in_array(substr($file, -3), ['zip', 'sql', '.gz'])) {
      $contents[] = $file;
    }
  }
  sort($contents);

  foreach ($contents as $entry) {
    if (!isset($buInfo) && (!isset($_GET['file']) || ($_GET['file'] == $entry)) && ($action !== 'backup') && ($action !== 'restore_local')) {
      $file_array = [
        'file' => $entry,
        'date' => date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)),
        'size' => number_format(filesize(DIR_FS_BACKUP . $entry)) . ' bytes',
        'compression' => $compressions[substr($entry, -3)] ?? TEXT_NO_EXTENSION,
      ];

      $buInfo = new objectInfo($file_array);
    }

    $onclick_link = (clone $GLOBALS['link'])->set_parameter('file', $entry);
    if (isset($buInfo->file) && ($entry == $buInfo->file)) {
      $onclick_link->set_parameter('action', 'restore');
      $icon = '<i class="fas fa-chevron-circle-right text-info"></i>';
    } else {
      $icon = '<a href="' . $onclick_link . '"><i class="fas fa-info-circle text-muted"></i></a>';
    }
?>
            <tr>
              <td onclick="document.location.href='<?= $onclick_link ?>'"><a href="<?=  (clone $onclick_link)->set_parameter('action', 'download') ?>"><i title="<?= ICON_FILE_DOWNLOAD ?>" class="fas fa-file-download text-muted"></i></a>&nbsp;<?= $entry ?></td>
              <td onclick="document.location.href='<?= $onclick_link ?>'"><?= date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)) ?></td>
              <td class="text-right" onclick="document.location.href='<?= $onclick_link ?>'"><?= sprintf(TEXT_INFO_BACKUP_SIZE, number_format(filesize(DIR_FS_BACKUP . $entry)/1024000, 2))  ?></td>
              <td class="text-right"><?= $icon ?></td>
            </tr>
<?php
  }
  $dir->close();
?>
          </tbody>
        </table>
      </div>

      <div class="row my-1">
        <div class="col"><?= sprintf(TEXT_BACKUP_DIRECTORY, DIR_FS_BACKUP) ?></div>
        <div class="col text-right mr-2"><?=
          ( isset($dir) && $dir_ok && ($action !== 'backup') ) ? $Admin->button(IMAGE_BACKUP, 'fas fa-download', 'btn-light mr-2', (clone $link)->set_parameter('action', 'backup')) : '',
          ( isset($dir) && ($action !== 'restore_local') ) ? $Admin->button(IMAGE_RESTORE, 'fas fa-upload', 'btn-light', (clone $link)->set_parameter('action', 'restore_local')) : ''
        ?></div>
      </div>

<?php
  if (defined('DB_LAST_RESTORE')) {
?>
        <hr>
        <div class="row my-1">
          <div class="col"><?= sprintf(TEXT_LAST_RESTORATION, DB_LAST_RESTORE) ?></div>
          <div class="col text-right mr-2">
            <?= new Form('forget', (clone $link)->set_parameter('action', 'forget')), new Button(TEXT_FORGET, 'fas fa-bell-slash', 'btn-light') ?></form>
          </div>
        </div>
<?php
  }
?>

    </div>

<?php
  if ($action_file = $Admin->locate('/infoboxes', $action)) {
    require DIR_FS_ADMIN . 'includes/components/infobox.php';
  }
?>

  </div>

  <script>
    var upload = document.querySelector('#upload');
    if (upload) {
      upload.addEventListener('change', function (event) {
        var n = this;
        while (n = n.nextElementSibling) {
          if (n.matches('.custom-file-label')) {
            n.innerHTML = event.target.files[0].name;
          }
        }
      });
    }
  </script>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
