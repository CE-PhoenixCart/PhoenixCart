<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $hooks->register_pipeline('loginRequired');

  if (!is_numeric($_GET['order_id'] ?? null)) {
    Href::redirect($Linker->build('account_history.php'));
  }

  $customer_info_query = $db->query(sprintf(<<<'EOSQL'
SELECT o.customers_id
 FROM orders o INNER JOIN orders_status s ON o.orders_status = s.orders_status_id
 WHERE s.public_flag = 1 AND o.orders_id = %d AND s.language_id = %d
EOSQL
    , (int)$_GET['order_id'], (int)$_SESSION['languages_id']));
  $customer_info = $customer_info_query->fetch_assoc();
  if ($customer_info['customers_id'] != $_SESSION['customer_id']) {
    Href::redirect($Linker->build('account_history.php'));
  }

  require language::map_to_translation('account_history_info.php');

  $order = new order($_GET['order_id']);

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
