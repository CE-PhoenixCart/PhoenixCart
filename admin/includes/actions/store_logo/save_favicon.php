<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $favicon = new upload('favicon');
  $favicon->set_extensions(['png']);
  $favicon->set_destination(DIR_FS_CATALOG . 'images/favicon/');
  $admin_hooks->cat('saveValidation', $favicon);

  if (!$favicon->parse() || !$favicon->save()) {
    return;
  }
  
  $array = ['256', '192', '128', '16'];

  include_once('includes/classes/ImageResize.php');

  foreach ($array as $size) {
    $open_location = DIR_FS_CATALOG . 'images/favicon/' . $favicon->filename;
    $save_location = DIR_FS_CATALOG . 'images/favicon/' . $size . '_' . $favicon->filename;
    
    $image = new \Gumlet\ImageResize($open_location);
    $image->resize($size, $size);
    $image->save($save_location);
  }

  $messageStack->add_session(SUCCESS_LOGO_UPDATED, 'success');
  $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($favicon->filename) . "' WHERE configuration_key = 'FAVICON_LOGO'");

  return $Admin->link('store_logo.php');
