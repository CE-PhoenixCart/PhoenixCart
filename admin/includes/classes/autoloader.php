<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class admin_autoloader {

    public static function index($index) {
      $index->find_all_files_under(DIR_FS_ADMIN . 'includes/modules');
      $index->find_all_files_under(DIR_FS_ADMIN . 'includes/classes');

      $overrides_directory = DIR_FS_ADMIN . 'includes/classes/override';
      if (is_dir($overrides_directory)) {
        $index->find_all_files_under($overrides_directory);
      }

      return $index;
    }

    public static function register($directory = null) {
      if (!class_exists('class_index')) {
        require DIR_FS_CATALOG . 'includes/system/class_index.php';
      }

      return static::index(new class_index(
        $directory ?? DIR_FS_ADMIN . 'includes/'))->register();
    }

  }
