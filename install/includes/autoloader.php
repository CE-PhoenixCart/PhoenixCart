<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class install_autoloader {

    public static function index($index) {
      $index->find_all_files_under(DIR_FS_CATALOG . 'includes/classes');
      $index->find_all_files_under(DIR_FS_CATALOG . 'includes/system/versioned');
      $index->find_all_files_under(__DIR__ . '/classes');

      return $index;
    }

    public static function register($directory = null) {
      if (!class_exists('class_index')) {
        require DIR_FS_CATALOG . 'includes/system/class_index.php';
      }

      return static::index(new class_index(
        $directory ?? DIR_FS_CATALOG . 'includes/'))->register();
    }

  }
