<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Versions {

    protected static $versions = [];
    protected static $loaders = [
      'Phoenix' => 'Versions::load_phoenix',
    ];

    public static function get($name = 'Phoenix') {
      if (!isset(static::$versions[$name])) {
        if (isset(static::$loaders[$name]) && is_callable(static::$loaders[$name])) {
          static::load($name);
        } else {
          return null;
        }
      }

      return static::$versions[$name];
    }

    public static function has($name) {
      return isset(static::$versions[$name]);
    }

    public static function load($name, $loader = null) {
      static::$versions[$name] = call_user_func($loader ?? static::$loaders[$name]);
    }

    public static function set($name, $version) {
      static::$versions[$name] = $version;
    }

    public static function register_loader($name, $loader) {
      static::$loaders[$name] = $loader;
    }

    protected static function load_phoenix() {
      return trim(file_get_contents(DIR_FS_CATALOG . 'includes/version.php'));
    }

  }
