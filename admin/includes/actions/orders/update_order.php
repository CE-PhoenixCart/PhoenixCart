<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $oID = Text::input($_GET['oID']);
  $status = Text::input($_POST['status']);
  $comments = Text::prepare($_POST['comments']);

  $check_status = $db->query("SELECT * FROM orders WHERE orders_id = " . (int)$oID)->fetch_assoc();

  if ( ($check_status['orders_status'] != $status) || !Text::is_empty($comments)) {
    if ($check_status['orders_status'] != $status) {
      $db->query("UPDATE orders SET orders_status = '" . $db->escape($status) . "', last_modified = NOW() WHERE orders_id = " . (int)$oID);
    }

    $check_status['status_name'] = order_status::fetch_name($status);
    $check_status['notify_comments'] = $comments;

    if (isset($_POST['notify']) && ('on' === $_POST['notify']) && Notifications::notify('update_order', $check_status)) {
      $customer_notified = 1;
    } else {
      $customer_notified = 0;
    }

    $db->perform('orders_status_history', [
      'orders_id' => (int)$oID,
      'orders_status_id' => $status,
      'date_added' => 'NOW()',
      'customer_notified' => (int)$customer_notified,
      'comments' => $comments,
    ]);

    $order_updated = true;
  } else {
    $order_updated = false;
  }

  $admin_hooks->cat('updateOrderAction');

  Href::redirect($Admin->link('orders.php')->retain_query_except()->set_parameter('action', 'edit'));
