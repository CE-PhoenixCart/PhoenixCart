<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $zone_id = Text::input($_GET['cID']);

  $db->query("DELETE FROM zones WHERE zone_id = " . (int)$zone_id);

  return $link;
