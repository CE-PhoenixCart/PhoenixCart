<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $option_id = Text::input($_GET['option_id']);

  $db->query("DELETE FROM products_options WHERE products_options_id = " . (int)$option_id);

  return $link;
