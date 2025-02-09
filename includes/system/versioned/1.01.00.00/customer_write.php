<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class customer_write extends customer_query {

    const FOREIGN_KEYS = [
      'customers_id' => [ 'address_book' ],
    ];

    const IDENTIFIER_SUFFIX = '_id';

    public static function create($db_tables, &$customer_details = []) {
      $foreign_keys = self::FOREIGN_KEYS;
      $tables = array_reverse(array_keys(static::$table_aliases));

      unset($db_tables['customers_info']);
      $parameters = [
        'data' => &$customer_details,
        'db' => &$db_tables,
        'keys' => &$foreign_keys,
        'tables' => &$tables,
      ];
      $GLOBALS['all_hooks']->cat('accountCreationTables', $parameters);

      foreach ($tables as $db_table) {
        if (!isset($db_tables[$db_table])) {
          continue;
        }

        $GLOBALS['db']->perform($db_table, $db_tables[$db_table]);
        $key = $db_table . self::IDENTIFIER_SUFFIX;
        $customer_details[$key] = mysqli_insert_id($GLOBALS['db']);
        if (isset($foreign_keys[$key]) && is_array($foreign_keys[$key])) {
          foreach ($foreign_keys[$key] as $table) {
            $db_tables[$table][$key] = $customer_details[$key];
          }
        }
      }
    }

    public static function update($db_tables, $criteria = []) {
      $foreign_keys = self::FOREIGN_KEYS;
      $parameters = [
        'db' => &$db_tables,
        'criteria' => &$criteria,
        'keys' => &$foreign_keys,
      ];

      $GLOBALS['all_hooks']->cat('accountUpdateTables', $parameters);

      // do not update columns that are null
      $db_tables = array_map(function ($value) {
        return array_filter($value, function ($v) {
          return isset($v);
        });
      }, $db_tables);
      $db_tables = array_filter($db_tables);

      foreach ($foreign_keys as $foreign_key => $tables) {
        foreach ($tables as $db_table) {
          Guarantor::guarantee_subarray($criteria, $db_table);
          if (!isset($criteria[$db_table][$foreign_key])) {
            $foreign_table = Text::rtrim_once($foreign_key, self::IDENTIFIER_SUFFIX);
            $criteria[$db_table][$foreign_key] = $criteria[$foreign_table][$foreign_key];
          }
        }
      }

      // the values of $criteria should be arrays
      // remove nulls and empty arrays and falsey values
      // but the only falsey values that should appear are nulls and empty arrays
      $criteria = array_filter($criteria);

      foreach ($db_tables as $db_table => $column_values) {
        $GLOBALS['db']->perform($db_table, $column_values, 'update',
          self::build_criteria($db_table, $criteria[$db_table]));
      }
    }

  }