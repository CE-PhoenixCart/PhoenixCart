<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $dir = DIR_FS_CATALOG . "templates/{$tpl}/includes/languages/";
  $file = $_GET['file'];
  
  $path = Path::normalize(realpath("{$dir}{$file}"));
  
  if (Text::is_prefixed_by($path, $dir)) {
    unlink($dir . $file);
  
    $messageStack->add_session(sprintf(FILE_DELETED_FROM_TEMPLATE, $file, $tpl), 'error');
  }
  
  $lang = $_GET['lang'] ?? 'english';
  return $Admin->link('language_explorer.php', ['lngdir' => $lang]);
