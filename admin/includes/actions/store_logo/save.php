<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $store_logo = new upload('store_logo');
  $store_logo->set_extensions(['png', 'gif', 'jpg', 'jpeg', 'svg', 'webp']);
  $store_logo->set_destination(DIR_FS_CATALOG . 'images/');
  $admin_hooks->cat('saveValidation', $store_logo);

  if (!$store_logo->parse() || !$store_logo->save()) {
    return;
  }

  $messageStack->add_session(SUCCESS_LOGO_UPDATED, 'success');
  $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($store_logo->filename) . "' WHERE configuration_key = 'STORE_LOGO'");

  return $Admin->link('store_logo.php');
