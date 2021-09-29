<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $products_options_id = Text::input($_POST['products_options_id']);

  foreach ($languages as $l) {
    $option_name = Text::prepare($_POST['option_name'][$l['id']]);
    $sort_order = Text::input($_POST['sort_order'][$l['id']]);

    $db->perform('products_options', [
      'products_options_id' => (int)$products_options_id,
      'products_options_name' => $option_name,
      'language_id' => (int)$l['id'],
      'sort_order' => $sort_order,
    ]);
  }

  return $link;
