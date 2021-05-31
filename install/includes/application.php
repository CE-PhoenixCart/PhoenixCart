<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  error_reporting(E_ALL);

// set default timezone if none exists (PHP throws an E_WARNING)
  if (strlen(ini_get('date.timezone')) < 1) {
    date_default_timezone_set(@date_default_timezone_get());
  }

  if (file_exists('includes/configure.php')) {
    include 'includes/configure.php';
  }

  if (!defined('DIR_FS_CATALOG')) {
    define('DIR_FS_CATALOG', rtrim(realpath(dirname(__DIR__, 2)), '\/') . '/');
  }
  require 'includes/autoloader.php';
  $install_index = install_autoloader::register();

  const DEFAULT_LANGUAGE = 'en';
  $locale = language::negotiate(array_flip(array_filter(
    array_diff(scandir('includes/translations/'), ['.', '..']),
    function ($v) {
      return file_exists("includes/translations/$v/translations.php");
    })));

  require "includes/translations/$locale/translations.php";
  if (file_exists("includes/translations/$locale/$page_contents")) {
    include "includes/translations/$locale/$page_contents";
  }

  const PHP_VERSION_MIN = '7.0';
  const PHP_VERSION_MAX = '8.0';
