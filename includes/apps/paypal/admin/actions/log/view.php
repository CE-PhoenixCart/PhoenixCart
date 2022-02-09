<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  if ( isset($_GET['lID']) && is_numeric($_GET['lID']) ) {
    $log_query = $GLOBALS['db']->query("select l.*, unix_timestamp(l.date_added) as date_added, c.customers_firstname, c.customers_lastname from oscom_app_paypal_log l left join customers c on (l.customers_id = c.customers_id) where id = '" . (int)$_GET['lID'] . "'");

    if ( mysqli_num_rows($log_query) ) {
      $log = $log_query->fetch_assoc();

      $log_request = [];

      $req = explode("\n", $log['request']);

      foreach ( $req as $r ) {
        $p = explode(':', $r, 2);

        $log_request[$p[0]] = $p[1];
      }

      $log_response = [];

      $res = explode("\n", $log['response']);

      foreach ( $res as $r ) {
        $p = explode(':', $r, 2);

        $log_response[$p[0]] = $p[1];
      }

      $content = 'log_view.php';
    }
  }
