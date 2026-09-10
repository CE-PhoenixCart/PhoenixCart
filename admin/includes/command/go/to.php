<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

$target = strtolower(trim($args));

if ($target === '') {
  Href::redirect($GLOBALS['Admin']->link('index.php'));
}

$lang_dir_path = DIR_FS_ADMIN . "includes/languages/{$_SESSION['language']}/modules/boxes";

if (is_dir($lang_dir_path) && ($lang_dir = dir($lang_dir_path))) {
  while (($file = $lang_dir->read()) !== false) {
    $path = "{$lang_dir->path}/$file";

    if (!is_dir($path) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
      include_once $path;
    }
  }
  $lang_dir->close();
}

$map = [];

$box_dir = DIR_FS_ADMIN . 'includes/boxes';

if ($dir = dir($box_dir)) {
  $files = [];

  while ($file = $dir->read()) {
    $path = "{$dir->path}/$file";

    if (!is_dir($path) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
      $files[] = $file;
    }
  }

  $dir->close();
  
  natcasesort($files);
  foreach ($files as $file) {
    include $box_dir . '/' . $file;
    if (!empty($cl_box_groups) && is_array($cl_box_groups)) {
      foreach ($cl_box_groups as $group) {
        if (empty($group['apps'])) {
          continue;
        }

        foreach ($group['apps'] as $app) {
          if (empty($app['title']) || empty($app['link'])) {
            continue;
          }

          $map[strtolower(trim(strip_tags($app['title'])))] = $app['link'];
        }
      }
    }
  }
}

// special cases
$map['logoff'] = $GLOBALS['Admin']->link('login.php', ['action' => 'logoff']);

if (!isset($map[$target])) {
  Href::redirect($GLOBALS['Admin']->link('index.php'));
}

Href::redirect($map[$target]);