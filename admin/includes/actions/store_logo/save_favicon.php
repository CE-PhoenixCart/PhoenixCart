<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $favicon = new upload('favicon');
  $favicon->set_extensions(['png', 'gif', 'jpg', 'svg', 'webp']);
  $favicon->set_destination(DIR_FS_CATALOG . 'images/');
  $admin_hooks->cat('saveValidation', $favicon);

  if (!$favicon->parse() || !$favicon->save()) {
    return;
  }

  $messageStack->add_session(SUCCESS_LOGO_UPDATED, 'success');
  $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($favicon->filename) . "' WHERE configuration_key = 'FAVICON_LOGO'");

  return $Admin->link('store_logo.php');
