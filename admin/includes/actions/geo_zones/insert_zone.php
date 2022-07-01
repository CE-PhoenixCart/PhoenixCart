<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $geo_zone_name = Text::prepare($_POST['geo_zone_name']);
  $geo_zone_description = Text::prepare($_POST['geo_zone_description']);

  $db->query("INSERT INTO geo_zones (geo_zone_name, geo_zone_description, date_added) VALUES ('" . $db->escape($geo_zone_name) . "', '" . $db->escape($geo_zone_description) . "', NOW())");
  $new_zone_id = mysqli_insert_id($db);

  return $link->set_parameter('zID', (int)$new_zone_id);
