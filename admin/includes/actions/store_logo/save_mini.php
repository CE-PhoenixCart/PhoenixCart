<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $mini_logo = new upload('mini_logo');
  $mini_logo->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
  $mini_logo->set_destination(DIR_FS_CATALOG . 'images/');
  $admin_hooks->cat('saveValidation', $mini_logo);

  if (!$mini_logo->parse() || !$mini_logo->save()) {
    return;
  }

  $messageStack->add_session(SUCCESS_LOGO_UPDATED, 'success');
  $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($mini_logo->filename) . "' WHERE configuration_key = 'MINI_LOGO'");

  return $Admin->link('store_logo.php');
