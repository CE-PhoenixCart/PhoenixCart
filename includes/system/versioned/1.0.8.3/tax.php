<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Tax {

    protected static $taxes = [];

    public static function add($price, $tax) {
      return ($tax > 0)
           ? $price + static::calculate($price, $tax)
           : $price;
    }

    public static function calculate($price, $tax) {
      return $price * $tax / 100;
    }

    public static function fetch($class_id, $country_id, $zone_id) {
      $tax_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT SUM(tax_rate) AS rate, GROUP_CONCAT(tax_description SEPARATOR ' + ') AS description
 FROM tax_rates tr
   LEFT JOIN zones_to_geo_zones za ON (tr.tax_zone_id = za.geo_zone_id)
   LEFT JOIN geo_zones tz ON (tz.geo_zone_id = tr.tax_zone_id)
 WHERE (za.zone_country_id IS NULL OR za.zone_country_id = 0 OR za.zone_country_id = %d)
   AND (za.zone_id IS NULL OR za.zone_id = 0 OR za.zone_id = %d)
   AND tr.tax_class_id = %d
 GROUP BY tr.tax_priority
 ORDER BY tr.tax_priority
EOSQL
        , (int)$country_id, (int)$zone_id, (int)$class_id));
      if (!mysqli_num_rows($tax_query)) {
        return [
          'rate' => 0,
          'description' => TEXT_UNKNOWN_TAX_RATE,
        ];
      }

      $taxes = $GLOBALS['db']->fetch_all($tax_query);
      $tax_multiplier = 1.0;
      foreach (array_column($taxes, 'rate') as $tax_rate) {
        $tax_multiplier *= 1.0 + ($tax_rate / 100);
      }

      return [
        'rate' => ($tax_multiplier - 1.0) * 100,
        'description' => implode(' + ', array_column($taxes, 'description')),
      ];
    }

    public static function fetch_classes() {
      return $GLOBALS['db']->fetch_all("SELECT tax_class_id AS id, tax_class_title AS text FROM tax_class ORDER BY tax_class_title");
    }

    /**
     * Format tax rate, possibly padded with trailing zeroes
     * @param numeric $value
     * @param int $padding
     * @return string
     */
    public static function format($value, int $padding = TAX_DECIMAL_PLACES) {
      if (false === ($decimal_position = strpos($value, '.'))) {
        $value .= '.';
      } else {
        $value = rtrim($value, '0');
        $decimal_position++;
        $padding += $decimal_position - strlen($value);
      }


      if ($padding > 0) {
        return $value . str_repeat('0', $padding);
      }

      return rtrim($value, '.');
    }

    public static function get($class_id, $country_id, $zone_id) {
      if (!isset(static::$taxes[$class_id][$country_id][$zone_id])) {
        Guarantor::guarantee_all(
          static::$taxes, $class_id, $country_id
          )[$zone_id] = static::fetch($class_id, $country_id, $zone_id);
      }

      return static::$taxes[$class_id][$country_id][$zone_id];
    }

    public static function get_class_title($tax_class_id) {
      if ($tax_class_id == '0') {
        return TEXT_NONE;
      }

      $class = $GLOBALS['db']->query("SELECT tax_class_title FROM tax_class WHERE tax_class_id = " . (int)$tax_class_id)->fetch_assoc();

      return $class['tax_class_title'];
    }

    public static function get_description($class_id, $country_id, $zone_id) {
      return static::get($class_id, $country_id, $zone_id)['description'];
    }

    public static function get_rate($class_id, $country_id = null, $zone_id = null) {
      if ( is_null($country_id) && is_null($zone_id) ) {
        global $customer;

        if (isset($customer) && ($customer instanceof customer)) {
          $country_id = $customer->get('country_id');
          $zone_id = $customer->get('zone_id');
        } else {
          $country_id = STORE_COUNTRY;
          $zone_id = STORE_ZONE;
        }
      }

      return static::get($class_id, $country_id, $zone_id)['rate'];
    }

    public static function price($price, $tax) {
      return (DISPLAY_PRICE_WITH_TAX === 'true')
           ? static::add($price, $tax)
           : $price;
    }

  }
