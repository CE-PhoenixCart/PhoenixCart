<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $zone_id = (int)Text::input($_GET['cID']);
  $zone_country_id = Text::input($_POST['zone_country_id']);
  $zone_code = Text::input($_POST['zone_code']);
  $zone_name = Text::prepare($_POST['zone_name']);

  $db->query("UPDATE zones SET zone_country_id = " . (int)$zone_country_id . ", zone_code = '" . $db->escape($zone_code) . "', zone_name = '" . $db->escape($zone_name) . "' WHERE zone_id = " . (int)$zone_id);

  return $link->set_parameter('cID', "$zone_id");
