<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  $content = 'configure.php';

  $modules = $OSCOM_PayPal->getModules();
  $modules[] = 'G';

  $default_module = 'G';

  foreach ( $modules as $m ) {
    if ( $OSCOM_PayPal->isInstalled($m) ) {
      $default_module = $m;
      break;
    }
  }

  $current_module = (isset($_GET['module']) && in_array($_GET['module'], $modules)) ? $_GET['module'] : $default_module;

  if ( !defined('OSCOM_APP_PAYPAL_TRANSACTIONS_ORDER_STATUS_ID') ) {
    $check_query = $GLOBALS['db']->query("select orders_status_id from orders_status where orders_status_name = 'PayPal [Transactions]' limit 1");

    if (mysqli_num_rows($check_query) < 1) {
      $status_query = $GLOBALS['db']->query("select max(orders_status_id) as status_id from orders_status");
      $status = $status_query->fetch_assoc();

      $status_id = $status['status_id']+1;

      foreach (language::load_all() as $lang) {
        $GLOBALS['db']->query("insert into orders_status (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'PayPal [Transactions]')");
      }

      $flags_query = $GLOBALS['db']->query("describe orders_status public_flag");
      if (mysqli_num_rows($flags_query) == 1) {
        $GLOBALS['db']->query("update orders_status set public_flag = 0 and downloads_flag = 0 where orders_status_id = '" . $status_id . "'");
      }
    } else {
      $check = $check_query->fetch_assoc();

      $status_id = $check['orders_status_id'];
    }

    $OSCOM_PayPal->saveParameter('OSCOM_APP_PAYPAL_TRANSACTIONS_ORDER_STATUS_ID', $status_id);
  }

  if ( !defined('OSCOM_APP_PAYPAL_VERIFY_SSL') ) {
    $OSCOM_PayPal->saveParameter('OSCOM_APP_PAYPAL_VERIFY_SSL', '1');
  }

  if ( !defined('OSCOM_APP_PAYPAL_PROXY') ) {
    $OSCOM_PayPal->saveParameter('OSCOM_APP_PAYPAL_PROXY', '');
  }

  if ( !defined('OSCOM_APP_PAYPAL_GATEWAY') ) {
    $OSCOM_PayPal->saveParameter('OSCOM_APP_PAYPAL_GATEWAY', '1');
  }

  if ( !defined('OSCOM_APP_PAYPAL_LOG_TRANSACTIONS') ) {
    $OSCOM_PayPal->saveParameter('OSCOM_APP_PAYPAL_LOG_TRANSACTIONS', '1');
  }
