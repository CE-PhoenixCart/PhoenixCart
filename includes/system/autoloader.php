<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class catalog_autoloader {

    public static function index($index) {
      $index->find_all_hooks_under(DIR_FS_CATALOG . 'includes/hooks/');
      $index->find_all_templates_under(DIR_FS_CATALOG . 'templates');

      $index->find_all_files_under(DIR_FS_CATALOG . 'includes/modules');
      $index->find_all_files_under(DIR_FS_CATALOG . 'includes/classes');
      $index->find_all_actions_under(DIR_FS_CATALOG . 'includes/actions');
      $index->find_all_files_under(DIR_FS_CATALOG . 'includes/system/versioned');

      $overrides_directory = DIR_FS_CATALOG . 'includes/system/override';
      if (is_dir($overrides_directory)) {
        $index->find_all_files_under($overrides_directory);
      }

      return $index;
    }

    public static function register($directory = null, $translate = null) {
      if (!class_exists('class_index')) {
        require DIR_FS_CATALOG . 'includes/system/class_index.php';
      }

      return static::index(new class_index(
        $directory ?? DIR_FS_CATALOG . 'includes/'))->register();
    }

  }
