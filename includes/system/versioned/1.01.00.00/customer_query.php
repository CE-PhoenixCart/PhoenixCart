<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class customer_query extends read_query {

    public static $table_aliases = [
      'address_book' => 'ab',
      'countries' => 'co',
      'customers' => 'c',
      'customers_info' => 'ci',
      'products_notifications' => 'pn',
      'zones' => 'z',
    ];

    public static function build_joins($db_tables, $criteria) {
      $sql = '';

      if (!isset($criteria['address_book_id']) && !isset($db_tables['customers']) && isset($db_tables['address_book'], $criteria['customers_id'])) {
        $db_tables['customers'] = [];
      }

      if (isset($db_tables['customers'])) {
        $sql .= ' customers ' . static::determine_alias('customers');
      }

      if (isset($db_tables['address_book'])) {
        $suffix = '';
        if (isset($db_tables['customers'])) {
          $sql .= ' LEFT JOIN';
          $suffix = ' ON ' . static::determine_alias('customers') . '.customers_id = ' . static::determine_alias('address_book') . '.customers_id';
          if (!isset($criteria['address_book_id'])) {
            $suffix .= ' AND ' . static::determine_alias('customers') . '.customers_default_address_id = ' . static::determine_alias('address_book') . '.address_book_id';
          }
        }
        $sql .= ' address_book ' . static::determine_alias('address_book') . $suffix;
      }

      if (isset($db_tables['zones'])) {
        if (isset($db_tables['address_book'])) {
          $sql .= ' LEFT JOIN zones ' . static::determine_alias('zones')
          . ' ON ' . static::determine_alias('address_book') . '.entry_zone_id = '
            . static::determine_alias('zones') . '.zone_id';
        }
      }

      if (isset($db_tables['countries'])) {
        if (isset($db_tables['address_book'])) {
          $sql .= ' LEFT JOIN countries ' . static::determine_alias('countries')
          . ' ON ' . static::determine_alias('address_book') . '.entry_country_id = '
            . static::determine_alias('countries') . '.countries_id';
        }
      }

      if (isset($db_tables['customers_info'])) {
        if (isset($db_tables['customers'])) {
          $sql .= ' INNER JOIN customers_info ' . static::determine_alias('customers_info')
                . ' ON ' . static::determine_alias('customers') . '.customers_id = '
                . static::determine_alias('customers_info') . '.customers_info_id';
        } elseif (isset($db_tables['address_book'])) {
          $sql .= ' INNER JOIN customers_info ' . static::determine_alias('customers_info')
                . ' ON ' . static::determine_alias('address_book') . '.customers_id = '
                . static::determine_alias('customers_info') . '.customers_info_id';
        }
      }

      return $sql;
    }

  }
