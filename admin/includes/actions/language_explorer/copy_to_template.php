<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $dir = DIR_FS_CATALOG . "includes/languages/";
  $file = $_GET['file'];
  
  $path = Path::normalize(realpath("{$dir}{$file}"));
  
  if (Text::is_prefixed_by($path, $dir)) {
    $source = "{$dir}{$file}";
    $destination = DIR_FS_CATALOG . "templates/{$tpl}/includes/languages/{$file}";

    @mkdir(dirname($destination), defined('DEFAULT_UNIX_PERMISSIONS') ? DEFAULT_UNIX_PERMISSIONS : 0755, true);
    copy($source, $destination);
  
    $messageStack->add_session(sprintf(FILE_COPIED_TO_TEMPLATE, $source, $tpl, $destination), 'success');
  }
  else {
    error_log("Bad file [{$file}] requested.");
    $messageStack->add(ERROR_FILE_NOT_ACCEPTABLE, 'error');
    
    return;
  }
  
  $lang = $_GET['lang'] ?? 'english';
  return $Admin->link('language_explorer.php', ['lngdir' => $lang]);
  