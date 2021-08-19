<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $products_id = Text::input($_POST['products_id']);
  $options_id = Text::input($_POST['options_id']);
  $values_id = Text::input($_POST['values_id']);
  $value_price = Text::input($_POST['value_price']);
  $price_prefix = Text::input($_POST['price_prefix']);

  $db->perform('products_attributes', [
    'products_id' => (int)$products_id,
    'options_id' => (int)$options_id,
    'options_values_id' => (int)$values_id,
    'options_values_price' => (float)$value_price,
    'price_prefix' => $price_prefix,
  ]);

  $products_attributes_id = mysqli_insert_id($db);

  if (DOWNLOAD_ENABLED == 'true') {
    $products_attributes_filename = Text::input($_POST['products_attributes_filename']);
    $products_attributes_maxdays = Text::input($_POST['products_attributes_maxdays']);
    $products_attributes_maxcount = Text::input($_POST['products_attributes_maxcount']);

    if (!Text::is_empty($products_attributes_filename)) {
      $db->perform('products_attributes_download', [
        'products_attributes_id' => (int)$products_attributes_id,
        'products_attributes_filename' => $products_attributes_filename,
        'products_attributes_maxdays' => (int)$products_attributes_maxdays,
        'products_attributes_maxcount' => (int)$products_attributes_maxcount,
      ]);
    }
  }

  return $link;
