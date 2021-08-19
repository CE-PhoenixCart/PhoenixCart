<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $option_id = Text::input($_POST['option_id']);

  foreach ($languages as $l) {
    $option_name = Text::prepare($_POST['option_name'][$l['id']]);
    $sort_order = Text::input($_POST['sort_order'][$l['id']]);

    $db->query("UPDATE products_options SET products_options_name = '" . $db->escape($option_name) . "', sort_order = '" . $db->escape($sort_order) . "' WHERE products_options_id = " . (int)$option_id . " AND language_id = " . (int)$l['id']);
  }

  return $link;