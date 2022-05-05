<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $oID = Text::input($_GET['oID']);

  $status = $db->query("SELECT COUNT(*) AS count FROM orders WHERE orders_status = " . (int)$oID)->fetch_assoc();

  $remove_status = true;
  if ($oID == DEFAULT_ORDERS_STATUS_ID) {
    $remove_status = false;
    $messageStack->add(ERROR_REMOVE_DEFAULT_ORDER_STATUS, 'error');
  } elseif ($status['count'] > 0) {
    $remove_status = false;
    $messageStack->add(ERROR_STATUS_USED_IN_ORDERS, 'error');
  } else {
    $history = $db->query("SELECT COUNT(*) AS count FROM orders_status_history WHERE orders_status_id = " . (int)$oID)->fetch_assoc();
    if ($history['count'] > 0) {
      $remove_status = false;
      $messageStack->add(ERROR_STATUS_USED_IN_HISTORY, 'error');
    }
  }
