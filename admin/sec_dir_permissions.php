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
  
  require 'includes/segments/process_action.php';

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
