<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $zID = Text::input($_GET['zID']);

  $db->query("DELETE FROM geo_zones WHERE geo_zone_id = " . (int)$zID);
  $db->query("DELETE FROM zones_to_geo_zones WHERE geo_zone_id = " . (int)$zID);

  return $link;
