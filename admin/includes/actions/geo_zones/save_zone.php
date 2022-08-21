<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $zID = Text::input($_GET['zID']);
  $geo_zone_name = Text::prepare($_POST['geo_zone_name']);
  $geo_zone_description = Text::prepare($_POST['geo_zone_description']);

  $db->query("UPDATE geo_zones SET geo_zone_name = '" . $db->escape($geo_zone_name) . "', geo_zone_description = '" . $db->escape($geo_zone_description) . "', last_modified = NOW() WHERE geo_zone_id = " . (int)$zID);

  return $link->set_parameter('zID', (int)$zID);
