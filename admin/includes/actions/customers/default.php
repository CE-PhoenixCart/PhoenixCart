<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_GET['cID'])) {
    $customer_details = $db->query($customer_data->build_read(
      $customer_data->list_all_capabilities(), 'both',
      [ 'id' => (int)$_GET['cID'] ]))->fetch_assoc();
  }
