<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  class products_query extends read_query {

    public static $table_aliases = [
      'categories' => ['alias' => 'c', 'id' => 'categories_id'],
      'manufacturers' => ['alias' => 'm', 'id' => 'manufacturers_id'],
      'specials' => ['alias' => 's', 'id' => 'products_id'],
      'tax_rates' => ['alias' => 'tr', 'id' => 'products_tax_class_id', 'local_id' => 'tax_class_id'],
      'zones_to_geo_zones' => 'gz',
      'products' => 'p',
      'products_description' => ['alias' => 'pd', 'id' => 'products_id'],
      'products_to_categories' => ['alias' => 'p2c', 'id' => 'products_id'],
    ];

    public static function build_joins($db_tables, $criteria) {
      $sql = ' products_description ' . static::determine_alias('products_description')
           . static::build_join('products_description', 'products', 'INNER');
      $joined = ['products_description' => true, 'products' => true];

      foreach (array_keys($criteria) as $table) {
        if (empty($joined[$table])) {
          // if not already joined, add criterion table as INNER because the criterion will mask out NULLs 
          $sql .= static::build_join('products', $table, 'INNER');
          $joined[$table] = true;
        }
      }

      foreach (array_diff(array_keys($db_tables), array_keys($joined)) as $table) {
        if (empty($joined[$table])) {
          $sql .= static::build_join('products', $table, 'LEFT');
          $joined[$table] = true;
        }
      }

      if (empty($joined['specials'])) {
        // always include specials for the price calculation
        $sql .= static::build_join('products', 'specials', 'LEFT');
      }

      // always include attributes for has_attribute
      $sql .= ' LEFT JOIN (SELECT products_id, COUNT(*) AS attribute_count FROM products_attributes GROUP BY products_id) a ON p.products_id = a.products_id';

      if ('true' === DISPLAY_PRICE_WITH_TAX) {
        if (isset($GLOBALS['customer']) && ($GLOBALS['customer'] instanceof customer)) {
          $country_id = $GLOBALS['customer']->get('country_id');
          $zone_id = $GLOBALS['customer']->get('zone_id');
        } else {
          $country_id = STORE_COUNTRY;
          $zone_id = STORE_ZONE;
        }

        $sql .= sprintf(<<<'EOSQL'
          LEFT JOIN (
            SELECT SUM(tr.tax_rate) AS tax_rate, tr.tax_class_id, gz.geo_zone_id
            FROM tax_rates tr
              LEFT JOIN zones_to_geo_zones gz ON tr.tax_zone_id = gz.geo_zone_id
                AND (gz.zone_country_id IS NULL OR gz.zone_country_id = 0 OR (gz.zone_country_id = %d AND gz.zone_id = %d))
          ) tax ON p.products_tax_class_id = tax.tax_class_id
EOSQL, (int)$country_id, (int)$zone_id);
      }

      return $sql;
    }

    // override default behavior, as products work differently
    public static function _build_columns($db_tables, $chain = []) {
      return 'm.*' . static::COLUMN_SEPARATOR . Product::COLUMNS;
    }

  }
