<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Zone {

    /**
     *
     * @param string $country_id
     * @return string
     */
    protected static function check_country($country_id = '') {
      $sql = " FROM zones WHERE";
      if ($country_id) {
        $sql .= " zone_country_id = " . (int)$country_id . " AND";
      }

      return "$sql zone_id = ";
    }

    /**
     *
     * @param numeric $zone_id
     * @param numeric|null $country_id If set, the country to restrict the results, checking if the zone and country match.
     * @param string $default
     * @return string
     */
    public static function fetch_name($zone_id, $country_id = null, $default = '') {
      $zone = $GLOBALS['db']->query("SELECT zone_name" . static::check_country($country_id) . (int)$zone_id)->fetch_assoc();

      return $zone['zone_name'] ?? $default;
    }

    /**
     *
     * @param numeric $zone_id
     * @param numeric|null $country_id If set, the country to restrict the results, checking if the zone and country match.
     * @param string $default
     * @return string
     */
    public static function fetch_code($zone_id, $country_id, $default) {
      $zone = $GLOBALS['db']->query("SELECT zone_code" . static::check_country($country_id) . (int)$zone_id)->fetch_assoc();

      return $zone['zone_code'] ?? $default;
    }

    /**
     *
     * @param numeric $country_id
     * @return array
     */
    public static function fetch_by_country($country_id) {
      return $GLOBALS['db']->fetch_all("SELECT zone_id AS id, zone_name AS text FROM zones WHERE zone_country_id = " . (int)$country_id . " ORDER BY zone_name");
    }

  }
