<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $tax_rates_id = Text::input($_GET['tID']);

  $db->query("DELETE FROM tax_rates WHERE tax_rates_id = " . (int)$tax_rates_id);

  return $link;
