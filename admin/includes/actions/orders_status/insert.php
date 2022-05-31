<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $next_id = $db->query("SELECT MAX(orders_status_id) AS orders_status_id FROM orders_status")->fetch_assoc();
  $orders_status_id = $next_id['orders_status_id'] + 1;

  foreach (array_column(language::load_all(), 'id') as $language_id) {
    $sql_data = [
      'orders_status_id' => $orders_status_id,
      'language_id' => $language_id,
      'orders_status_name' => Text::prepare($_POST['orders_status_name'][$language_id]),
      'public_flag' => ((isset($_POST['public_flag']) && ($_POST['public_flag'] == '1')) ? '1' : '0'),
      'downloads_flag' => ((isset($_POST['downloads_flag']) && ($_POST['downloads_flag'] == '1')) ? '1' : '0'),
    ];

    $db->perform('orders_status', $sql_data);
  }

  if (isset($_POST['default']) && ('on' === $_POST['default'])) {
    $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($orders_status_id) . "' WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
  }

  return $link->set_parameter('oID', $orders_status_id);
