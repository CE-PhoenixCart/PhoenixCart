<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $oID = Text::input($_GET['oID']);

  $orders_status = $db->query("SELECT configuration_value FROM configuration WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'")->fetch_assoc();

  if ($orders_status['configuration_value'] == $oID) {
    $db->query("UPDATE configuration SET configuration_value = '' WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
  }

  $db->query("DELETE FROM orders_status WHERE orders_status_id = " . (int)$oID);

  return $link;
