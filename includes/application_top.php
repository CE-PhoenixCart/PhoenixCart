<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

  include 'includes/configure.php';

  require 'includes/system/autoloader.php';
  $class_index = catalog_autoloader::register();

  require 'includes/functions/database.php';

// make a connection to the database... now
  $db = new Database() or die('Unable to connect to database server!');

  $hooks = new hooks('shop');
  $OSCOM_Hooks =& $hooks;
  $all_hooks =& $hooks;
  $hooks->register('system');
  foreach ($hooks->generate('startApplication') as $result) {
    if (!isset($result)) {
      continue;
    }

    if (is_string($result)) {
      $result = [ $result ];
    }

    if (is_array($result)) {
      foreach ($result as $path) {
        if (is_string($path ?? null) && file_exists($path)) {
          require $path;
        }
      }
    }
  }
