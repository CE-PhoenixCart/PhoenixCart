<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class geo_zone {

    public static function fetch_name($geo_zone_id) {
      if ($geo_zone_id == '0') {
        return TEXT_NONE;
      }

      return $GLOBALS['db']->query("SELECT geo_zone_name FROM geo_zones WHERE geo_zone_id = " . (int)$geo_zone_id)->fetch_assoc()['geo_zone_name'] ?? $geo_zone_id;
    }

    public static function fetch_options() {
      return $GLOBALS['db']->fetch_all("SELECT geo_zone_id AS id, geo_zone_name AS text FROM geo_zones ORDER BY geo_zone_name");
    }

  }
