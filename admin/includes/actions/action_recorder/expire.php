<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_GET['module']) && in_array($_GET['module'], $modules)) {
    if (is_object(${$_GET['module']})) {
      $expired_entries = ${$_GET['module']}->expireEntries();
    } else {
      $db->query("DELETE FROM action_recorder WHERE module = '" . $db->escape($_GET['module']) . "'");
      $expired_entries = mysqli_affected_rows($db);
    }
  } else {
    $expired_entries = 0;
    foreach ($modules as $module) {
      if (is_object(${$module})) {
        $expired_entries += ${$module}->expireEntries();
      }
    }
  }

  return $Admin->link('action_recorder.php');
