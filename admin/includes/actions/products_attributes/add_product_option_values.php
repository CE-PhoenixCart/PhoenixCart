<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $value_name_array = $_POST['value_name'];
  $sort_order_array = $_POST['sort_order'];
  $value_id = Text::input($_POST['value_id']);
  $option_id = Text::input($_POST['option_id']);

  foreach ($languages as $l) {
    $value_name = Text::prepare($value_name_array[$l['id']]);
    $sort_order = Text::input($sort_order_array[$l['id']]);

    $db->perform('products_options_values', [
      'products_options_values_id' => (int)$value_id,
      'language_id' => (int)$l['id'],
      'products_options_values_name' => $value_name,
      'sort_order' => $sort_order,
    ]);
  }

  $db->query("INSERT INTO products_options_values_to_products_options (products_options_id, products_options_values_id) VALUES (" . (int)$option_id . ", " . (int)$value_id . ")");

  return $link;
