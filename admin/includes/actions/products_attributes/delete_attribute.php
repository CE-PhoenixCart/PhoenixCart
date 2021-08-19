<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $attribute_id = Text::input($_GET['attribute_id']);

  $db->query("DELETE FROM products_attributes WHERE products_attributes_id = " . (int)$attribute_id);

// Always try to remove, even if downloads are no longer enabled
  $db->query("DELETE FROM products_attributes_download WHERE products_attributes_id = " . (int)$attribute_id);

  return $link;
