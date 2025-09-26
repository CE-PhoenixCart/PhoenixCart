<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("UPDATE customers SET status = " . (int)$_GET['flag'] . " WHERE customers_id = " . (int)$_GET['cID']);
  $db->query("DELETE FROM products_notifications WHERE customers_id = " . (int)$_GET['cID']);
  $db->query("DELETE FROM outgoing WHERE customer_id = " . (int)$_GET['cID']);

  return $Admin->link('customers.php')->retain_query_except(['action', 'flag']);
