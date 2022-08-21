<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $customer_data_groups_id = Text::input($_GET['cdgID']);

  $db->query("DELETE FROM customer_data_groups WHERE customer_data_groups_id = " . (int)$customer_data_groups_id);
  $db->query("DELETE FROM customer_data_groups_sequence WHERE customer_data_groups_id = " . (int)$customer_data_groups_id);

  return $link;
