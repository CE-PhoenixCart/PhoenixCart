<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $sql_data = [
    'name' => Text::prepare($_POST['name']),
    'code' => Text::prepare(substr($_POST['code'], 0, 2)),
    'image' => Text::prepare($_POST['image']),
    'directory' => Text::prepare($_POST['directory']),
    'sort_order' => (int)Text::input($_POST['sort_order']),
  ];

  $db->perform('languages', $sql_data);
  $lID = mysqli_insert_id($db);

// create additional language-specific records
  $db->query("INSERT INTO categories_description (categories_id, language_id, categories_name) SELECT categories_id, " . (int)$lID . ", categories_name FROM categories_description WHERE language_id = " . (int)$_SESSION['languages_id']);
  $db->query("INSERT INTO products_description (products_id, language_id, products_name, products_description, products_url) SELECT products_id, " . (int)$lID . ", products_name, products_description, products_url FROM products_description WHERE language_id = " . (int)$_SESSION['languages_id']);
  $db->query("INSERT INTO products_options (products_options_id, language_id, products_options_name) SELECT products_options_id, " . (int)$lID . ", products_options_name FROM products_options WHERE language_id = " . (int)$_SESSION['languages_id']);
  $db->query("INSERT INTO products_options_values (products_options_values_id, language_id, products_options_values_name) SELECT products_options_values_id, " . (int)$lID . ", products_options_values_name FROM products_options_values WHERE language_id = " . (int)$_SESSION['languages_id']);
  $db->query("INSERT INTO manufacturers_info (manufacturers_id, languages_id, manufacturers_url) SELECT manufacturers_id, " . (int)$lID . ", manufacturers_url FROM manufacturers_info WHERE languages_id = " . (int)$_SESSION['languages_id']);
  $db->query("INSERT INTO orders_status (orders_status_id, language_id, orders_status_name) SELECT orders_status_id, " . (int)$lID . ", orders_status_name FROM orders_status WHERE language_id = " . (int)$_SESSION['languages_id']);
  $db->query("INSERT INTO customer_data_groups (customer_data_groups_id, language_id, customer_data_groups_name, cdg_vertical_sort_order, customer_data_groups_width) SELECT customer_data_groups_id, " . (int)$lID . ", customer_data_groups_name, cdg_vertical_sort_order, customer_data_groups_width FROM customer_data_groups WHERE language_id = " . (int)$_SESSION['languages_id']);

  if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
    $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($sql_data['code']) . "' WHERE configuration_key = 'DEFAULT_LANGUAGE'");
  }

  return $link->set_parameter('lID', (int)$lID);
