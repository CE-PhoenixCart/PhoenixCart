<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class query {

    const WHERE = ' WHERE ';
    const ORDER_BY = ' ORDER BY ';
    const CRITERIA_INTERSECTION = ' AND ';
    const CRITERIA_UNION = ' OR ';
    const COLUMN_SEPARATOR = ', ';

    public static $table_aliases = [];

    public static function determine_alias($table_name) {
      return $GLOBALS['db']->escape(static::$table_aliases[$table_name]['alias']
          ?? static::$table_aliases[$table_name]
          ?? $table_name);
    }

    public static function determine_id($table_name) {
      return $GLOBALS['db']->escape(static::$table_aliases[$table_name]['id']
          ?? "{$table_name}_id");
    }

    public static function build_join($from, $to, $type = 'LEFT') {
      $to_alias = static::determine_alias($to);
      return " $type JOIN $to $to_alias USING (" . static::determine_id($to) . ')';
    }

    public static function add_criteria_tables($db_tables, $criteria) {
      foreach (array_keys($criteria) as $db_table) {
        if (!isset($db_tables[$db_table])) {
          $db_tables[$db_table] = [];
        }
      }

      return $db_tables;
    }

    public static function add_search_criteria($sql, $key, $db_tables) {
      $key = $GLOBALS['db']->escape($key);
      $criteria = [];
      foreach ($db_tables as $db_table => $columns) {
        $table_alias = static::determine_alias($db_table);
        foreach (array_keys($columns) as $name) {
          $criteria[] = "$table_alias.$name LIKE '%$key%'";
        }
      }

      if (empty($criteria)) {
        return $sql;
      }

      $where_position = strrpos($sql, self::WHERE);
      if (false === $where_position) {
        $sql .= self::WHERE;
      } elseif ($where_position + strlen(self::WHERE) + 1 < strlen($sql)) {
        $sql .= self::CRITERIA_INTERSECTION;
      }

      $sql .= '(' . implode(self::CRITERIA_UNION, $criteria) . ')';

      return $sql;
    }

    public static function add_order_by($criteria) {
      $sql = self::ORDER_BY;
      foreach ($criteria as $db_table => $column_directions) {
        $table_alias = static::determine_alias($db_table);
        foreach ($column_directions as $column => $direction) {
          $sql .= $table_alias . '.' . $GLOBALS['db']->escape($column);
          if (!empty($direction) && 'DESC' === strtoupper($direction)) {
            $sql .= ' DESC';
          }
          $sql .= self::COLUMN_SEPARATOR;
        }
      }

      return Text::rtrim_once($sql, self::COLUMN_SEPARATOR);
    }

    public static function build_specified_columns($db_tables) {
      $sql = '';

      foreach ($db_tables as $db_table => $columns) {
        $table_alias = static::determine_alias($db_table);
        foreach ($columns as $column => $alias) {
          $sql .= "$table_alias.$column";
          if (isset($alias)) {
            $sql .= " AS $alias";
          }
          $sql .= self::COLUMN_SEPARATOR;
        }
      }

      return $sql;
    }

    public static function build_columns($db_tables, $chain = []) {
      $sql = '';

      foreach (static::$table_aliases as $db_table => $table_alias) {
        if (isset($db_tables[$db_table])) {
          if (!is_string($table_alias)) {
            $table_alias = static::determine_alias($db_table);
          }
          $sql .= "$table_alias.*" . self::COLUMN_SEPARATOR;
        }
      }

      foreach (array_diff(array_keys($db_tables), array_keys(static::$table_aliases)) as $db_table) {
        $sql .= "$db_table.*" . self::COLUMN_SEPARATOR;
      }

      return $sql . static::build_specified_columns($db_tables);
    }

    public static function _build_columns($db_tables, $chain = []) {
      return Text::rtrim_once(static::build_columns($db_tables, $chain), self::COLUMN_SEPARATOR);
    }

    protected static function value($value) {
      // if an int, pass as int (int casting is safer than escaping);
      // otherwise, assume a string as SQL will convert strings helpfully
      return is_int($value)
           ? (int)$value
           : "'" . $GLOBALS['db']->escape($value) . "'";
    }

    protected static function _build_criteria($alias, $column_values) {
      $sql = '';

      foreach ($column_values as $column => $value) {
        if (!is_null($alias)) {
          $sql .= "$alias.";
        }

        $sql .= is_array($value)
              ? "$column IN (" . implode(', ', array_map([__CLASS__, 'value'], $value)) . ')'
              : "$column = " . static::value($value);
        $sql .= self::CRITERIA_INTERSECTION;
      }

      return $sql;
    }

    public static function build_criteria($db_table, $column_values) {
      return Text::rtrim_once(self::_build_criteria(null, $column_values), self::CRITERIA_INTERSECTION);
    }

    public static function build_where($criteria, $skip_alias = false) {
      $sql = '';

      if (empty($criteria)) {
        // do nothing
      } elseif (is_string($criteria)) {
        $sql .= $criteria;
      } elseif (is_array($criteria)) {
        $sql .= self::WHERE;

        if ($skip_alias && count($criteria) === 1) {
          $sql .= self::_build_criteria(null, reset($criteria));
        } else {
          foreach ($criteria as $db_table => $column_values) {
            $sql .= self::_build_criteria(static::determine_alias($db_table), $column_values);
          }
        }

        $sql = Text::rtrim_once($sql, self::CRITERIA_INTERSECTION);
      }

      return $sql;
    }

  }
