<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("INSERT INTO customer_data_groups_sequence VALUES (NULL)");
  $customer_data_groups_id = mysqli_insert_id($db);

  $first_language_id = key($_POST['customer_data_groups_name']);
  foreach ($_POST['customer_data_groups_name'] as $language_id => $customer_data_groups_name) {
// if use_first was checked, get all the values other than the name from the first group
    $index = empty($_POST['use_first']) ? $language_id : $first_language_id;

    $sql_data = [
      'customer_data_groups_id' => (int)$customer_data_groups_id,
      'language_id' => (int)$language_id,
      'customer_data_groups_name' => Text::prepare($customer_data_groups_name),
      'cdg_vertical_sort_order' => Text::input($_POST['cdg_vertical_sort_order'][$index]),
      'customer_data_groups_width' => Text::input($_POST['customer_data_groups_width'][$index]),
    ];

    $db->perform('customer_data_groups', $sql_data);
  }

  return $Admin->link('customer_data_groups.php');
