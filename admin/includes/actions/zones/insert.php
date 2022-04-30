<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $zone_country_id = Text::input($_POST['zone_country_id']);
  $zone_code = Text::input($_POST['zone_code']);
  $zone_name = Text::prepare($_POST['zone_name']);

  $db->query("INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (" . (int)$zone_country_id . ", '" . $db->escape($zone_code) . "', '" . $db->escape($zone_name) . "')");

  return $Admin->link();
