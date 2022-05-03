<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $whitelisted_directories = array_column($db->fetch_all("SELECT directory FROM sec_directory_whitelist"), 'directory');

  $admin_dir = basename(DIR_FS_ADMIN);

  if ($admin_dir !== 'admin') {
    $whitelisted_directories = array_map(function ($d) {
      return Text::is_prefixed_by($d, 'admin/')
           ? $admin_dir . substr($d, strlen('admin'))
           : $d;
    }, $whitelisted_directories);
  }

  function generate_phoenix_directories($path) {
    $path = rtrim($path, '/') . '/';

    $exclude_array = ['.', '..', '.DS_Store', 'Thumbs.db', '.github'];

    $results = [];

    if ($handle = opendir($path)) {
      while (false !== ($filename = readdir($handle))) {
        if (in_array($filename, $exclude_array)) {
          continue;
        }

        $pathname = "$path$filename";

        if (is_dir($pathname)) {
          yield [
            'name' => $pathname,
            'writable' => Path::is_writable($pathname),
            'whitelisted' => in_array(Text::ltrim_once($pathname, DIR_FS_CATALOG), $GLOBALS['whitelisted_directories']),
          ];

          yield from generate_phoenix_directories($pathname);
        }
      }

      closedir($handle);
    }

    return $results;
  }

  require 'includes/template_top.php';
?>

  <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th><?= TABLE_HEADING_DIRECTORIES ?></th>
          <th class="text-center"><?= TABLE_HEADING_RECOMMENDED ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach (generate_phoenix_directories(DIR_FS_CATALOG) as $file) {
        if (empty($_GET['show_all']) && ($file['whitelisted'] == $file['writable'])) {
          continue;
        }
      ?>
        <tr>
          <td><?= Text::ltrim_once($file['name'], DIR_FS_CATALOG) ?></td>
          <td class="text-center"><i class="fas fa-<?=
            $file['whitelisted'] ? 'edit' : 'lock',
            ' text-',
            ($file['whitelisted'] == $file['writable']) ? 'success' : 'danger'
          ?>"></i></td>
        </tr>
        <?php
      }
      ?>
      </tbody>
    </table>
  </div>

  <p><?= sprintf(TEXT_DIRECTORY, DIR_FS_CATALOG) ?></p>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
