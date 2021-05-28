<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  require 'includes/application.php';

  if (!empty($_GET['action'])) {
    switch ($_GET['action']) {
      case 'dbCheck':
        $db = new Database(
          trim($_GET['server']),
          trim($_GET['username']),
          trim($_GET['password']), '');

        if ($db->connect_errno) {
          exit("[[0|{$db->connect_error}]]");
        }

        $db->select_db(trim($_GET['name']));

        if ($db->errno) {
          exit("[[0|{$db->error}]]");
        }

        if ($version_query = mysqli_query($db, "SELECT VERSION() AS `v`")) {
          list($number) = explode('-', $version_query->fetch_assoc()['v']);
        } else {
          exit("[[0|{$db->error}]]");
        }

        if (version_compare($number, '5.7.7', '<')) {
          exit("[[-5|$version]]");
        }

        if (version_compare($number, '10.2.2', '>=')
         || version_compare($number, '10', '<'))
        {
          exit('[[1]]');
        }

        exit("[[-10|$version]]");

      case 'dbImport':
        $db = new Database(
          trim($_GET['server']),
          trim($_GET['username']),
          trim($_GET['password']), '');

        if ($db->connect_errno) {
          exit("[[0|{$db->connect_error}]]");
        }

        installer::set_time_limit(0);

        $db_name = trim($_GET['name']);
        if (!$db->select_db($db_name)) {
          $db->query("CREATE DATABASE " . $db->escape($db_name));
          if ($db->errno) {
            exit("[[0|{$db->error}]]");
          }

          if (!$db->select_db($db_name)) {
            exit('[[0|dbSelectError]]');
          }
        }

        installer::load_sql($db, __DIR__ . '/phoenix.sql');

        if (!$db->errno && (trim($_GET['importsample']) === '1')) {
          installer::load_sql($db, __DIR__ . '/phoenix_data_sample.sql');
        }

        if ($db->errno) {
          exit("[[0|$db_error]]");
        }

        exit('[[1]]');
    }
  }

  echo '[[-100|noActionError]]';
?>
