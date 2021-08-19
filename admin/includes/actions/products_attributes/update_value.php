<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $value_id = Text::input($_POST['value_id']);
  $option_id = Text::input($_POST['option_id']);

  foreach ($languages as $l) {
    $value_name = Text::prepare($_POST['value_name'][$l['id']]);
    $sort_order = Text::input($_POST['sort_order'][$l['id']]);

    $db->query("UPDATE products_options_values SET products_options_values_name = '" . $db->escape($value_name) . "', sort_order = '" . $db->escape($sort_order) . "' WHERE products_options_values_id = '" . $db->escape($value_id) . "' AND language_id = " . (int)$l['id']);
  }

  $db->query("UPDATE products_options_values_to_products_options SET products_options_id = " . (int)$option_id . "  WHERE products_options_values_id = " . (int)$value_id);

  return $link;
