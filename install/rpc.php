<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  require 'includes/application.php';

  if (!empty($_GET['action'])) {
    switch ($_GET['action']) {
      case 'dbCheck':
        try {
          $db = new Database(trim($_GET['server']), trim($_GET['username']), trim($_GET['password']), '');

          $version_query = mysqli_query($db, "SELECT VERSION() AS `v`");
          if (!$version_query) {
            exit("0|{$db->error}");
          }

          $version = $version_query->fetch_assoc()['v'];
          list($number) = explode('-', $version);

          if (version_compare($number, '5.7.7', '<')) {
            exit("-5|Version [{$version}] not at least MySQL 5.7.7");
          }

          if (version_compare($number, '10.2.2', '>=') || version_compare($number, '10', '<')) {
            exit("1|Success");
          }

          exit("-10|Version [{$version}] not at least MariaDB 10.2.2");

        } catch (mysqli_sql_exception $e) {
          exit($e->getCode() . '|' . $e->getMessage());
        }
      break;
      case 'dbImport':
        try {
          $db_name = trim($_GET['name']);
          $db = new Database(trim($_GET['server']), trim($_GET['username']), trim($_GET['password']), $db_name);

          goto dbInstall;
        } catch (mysqli_sql_exception $e) {
          try {
            $db = new Database(trim($_GET['server']), trim($_GET['username']), trim($_GET['password']), '');

            try {
              $db_name = trim($_GET['name']);
              
              $db->query("CREATE DATABASE " . $db->escape($db_name));

              try {
                $db->select_db($db_name);

                goto dbInstall;
              }
              catch (mysqli_sql_exception $e) {
                exit("1046|Could not select DB {$db_name}");
              }
            } catch (mysqli_sql_exception $e) {
              exit("1006|Could Not Create Database, please create {$db_name} manually");
            }
          }
          catch (mysqli_sql_exception $e) {
            exit($e->getCode() . '|' . $e->getMessage());
          }
        }

        dbInstall:
        
        installer::set_time_limit(0);
        installer::load_sql($db, __DIR__ . '/phoenix.sql');

        if (trim($_GET['importsample'] ?? '0') === '1') {
          installer::load_sql($db, __DIR__ . '/phoenix_data_sample.sql');
        }

        exit("1|Success");

      break;
    }
  }