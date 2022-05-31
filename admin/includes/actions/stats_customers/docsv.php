<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $filename = 'stats_customers.csv';
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=' . $filename);

  $output = fopen('php://output', 'w');
  fputcsv($output, CSV_HEADERS);

  $history_query = $db->query(sprintf(<<<'EOSQL'
SELECT o.customers_id, o.orders_id, o.date_purchased, ot.value AS order_total, s.orders_status_name
 FROM orders o
   INNER JOIN orders_total ot ON o.orders_id = ot.orders_id
   INNER JOIN orders_status s ON o.orders_status = s.orders_status_id
 WHERE ot.class = 'ot_total' AND s.language_id = %d AND o.customers_id = %d
 ORDER BY orders_id DESC
EOSQL
    , (int)$_SESSION['languages_id'], (int)$_GET['cID']));

  while ($history = $history_query->fetch_row()) {
    fputcsv($output, $history);
  }

  $admin_hooks->cat('doCsvAction');
  fclose($output);

  exit();
