<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $value_id = Text::input($_GET['value_id']);

  $db->query("DELETE FROM products_options_values WHERE products_options_values_id = " . (int)$value_id);
  $db->query("DELETE FROM products_options_values_to_products_options WHERE products_options_values_id = " . (int)$value_id);

  return $link;
