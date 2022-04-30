<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $tax_class_id = Text::input($_GET['tID']);

  $db->query("DELETE FROM tax_class WHERE tax_class_id = " . (int)$tax_class_id);

  return $link;
