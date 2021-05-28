<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Installer {

    public static function burn($path, $contents) {
      file_put_contents($path, $contents);
      @chmod($path, defined('READ_ONLY_PERMISSIONS') ? READ_ONLY_PERMISSIONS : 0644);
      if (File::is_writable($path)) {
        @chmod($path, defined('READ_ONLY_PERMISSIONS') ? READ_ONLY_PERMISSIONS & 0444 : 0444);
      }
    }

    public static function configure($key, $value) {
      global $db;

      $db->query(sprintf(
        "UPDATE configuration SET configuration_value = '%s' WHERE configuration_key = '%s'",
        $db->escape($value),
        $key));
    }

    public static function find_unwritable_files($directory) {
      $catalog_configuration_path = Path::normalize("$directory/includes/configure.php");
      if (!File::is_writable($catalog_configuration_path)) {
        @touch($catalog_configuration_path);
        @chmod($catalog_configuration_path, 0666);
      }

      $admin_configuration_path = Path::normalize("$directory/admin/includes/configure.php");
      if (!File::is_writable($admin_configuration_path)) {
        @touch($admin_configuration_path);
        @chmod($admin_configuration_path, 0666);
      }

      $files = [];
      if (!File::is_writable($catalog_configuration_path)) {
        $files[] = $catalog_configuration_path;
      }

      if (!File::is_writable($admin_configuration_path)) {
        $files[] = $admin_configuration_path;
      }

      return $files;
    }

    public static function load_sql($db, $sql_file) {
      if (!file_exists($sql_file)) {
        $db_error = 'SQL file does not exist: ' . $sql_file;
        return false;
      }

      foreach (
        explode(";\n",
          trim(implode('',
              array_filter(
                file($sql_file),
                function ($s) {
                  $s = trim($s);
                  return ('' !== $s) && ('#' !== $s[0]);
                })), "; \n\r\t\v\0")
        ) as $sql)
      {
        if (!$db->query($sql)) {
          return false;
        }
      }
    }

    public static function load_time_zones() {
      return array_map(function ($v) {
        return ['id' => $v, 'text' => str_replace('_', ' ', $v)];
      }, timezone_identifiers_list());
    }

    public static function set_time_limit($limit) {
      set_time_limit($limit);
    }

  }
