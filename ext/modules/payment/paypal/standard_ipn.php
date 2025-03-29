<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  IPN Listener
  Paypal Standard Payments
  Basic Paypal Payment Module for Phoenix Cart
  More sophisticated Paypal integration available at https://phoenixcart.org/forum/addons/

  author: John Ferguson @BrockleyJohn phoenix@cartmart.uk

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

chdir('../../../../');
require 'includes/application_top.php';

if ( !defined('MODULE_PAYMENT_PAYPAL_STANDARD_STATUS') || !in_array(MODULE_PAYMENT_PAYPAL_STANDARD_STATUS, ['True', 'False']) ) {
  exit;
}

$_SESSION['payment'] = 'paypal_standard';
$paypal_standard = new paypal_standard();

$result = false;

$seller_accounts = [MODULE_PAYMENT_PAYPAL_STANDARD_ID];
if ( !Text::is_empty(MODULE_PAYMENT_PAYPAL_STANDARD_PRIMARY_ID) ) {
  $seller_accounts[] = MODULE_PAYMENT_PAYPAL_STANDARD_PRIMARY_ID;
}

if ( (isset($_POST['receiver_email']) && in_array($_POST['receiver_email'], $seller_accounts)) || (isset($_POST['business']) && in_array($_POST['business'], $seller_accounts)) ) {
  $parameters = 'cmd=_notify-validate&';

  foreach ( $_POST as $key => $value ) {
    if ( $key != 'cmd' ) {
      $parameters .= $key . '=' . urlencode(stripslashes($value)) . '&';
    }
  }

  $parameters = substr($parameters, 0, -strlen('&'));

  $result = $paypal_standard->callAPI($paypal_standard->form_action_url, $parameters);
}

$log_params = [];

foreach ( $_POST as $key => $value ) {
  $log_params[$key] = stripslashes($value);
}

$paypal_standard->log('PS', '_notify-validate', ($result == 'VERIFIED') ? 1 : -1, $log_params, $result, MODULE_PAYMENT_PAYPAL_STANDARD_GATEWAY, true);

try {

  if ( $result == 'VERIFIED' ) {
    // write a verified record to order history
    $paypal_standard->verifyTransaction($_POST, true);

    $order_id = (int)$paypal_standard->orderid_from_invoice($_POST['invoice'] ?? '');
    $customer_id = (int)$_POST['custom'];
    $customer = new customer($customer_id);

    $check_query = $db->query("SELECT orders_status, customer_comments FROM orders WHERE orders_id = " . (int)$order_id . " AND customers_id = " . (int)$customer_id);

    if ($check = $check_query->fetch_assoc()) {
      if ( $check['orders_status'] == MODULE_PAYMENT_PAYPAL_STANDARD_PREPARE_ORDER_STATUS_ID ) {
        //error_log("order status {$check['orders_status']}" . ' ipn wins race: finishing order...');
        $order = new order($order_id);
        $order->info['order_status'] = DEFAULT_ORDERS_STATUS_ID;

        if ( MODULE_PAYMENT_PAYPAL_STANDARD_ORDER_STATUS_ID > 0 ) {
          $order->info['order_status'] = MODULE_PAYMENT_PAYPAL_STANDARD_ORDER_STATUS_ID;
        }

        $db->query("UPDATE orders SET orders_status = " . (int)$order->info['order_status'] . ", last_modified = NOW() WHERE orders_id = " . (int)$order_id);

        if ('true' === DOWNLOAD_ENABLED) {
          $downloads_query = $db->query("SELECT opd.orders_products_filename FROM orders o, orders_products op, orders_products_download opd WHERE o.orders_id = " . (int)$order_id . " AND o.customers_id = " . (int)$customer_id . " AND o.orders_id = op.orders_id AND op.orders_products_id = opd.orders_products_id AND opd.orders_products_filename != ''");

          switch (mysqli_num_rows($downloads_query)) {
            case 0:
              $order->content_type = 'physical';
              break;
            case count($order->products):
              $order->content_type = 'virtual';
              break;
            default:
              $order->content_type = 'mixed';
          }
        } else {
          $order->content_type = 'physical';
        }

        $_POST['comments'] = $order->info['comments'] = $check['customer_comments'];
        $hooks->register_pipeline('after');
        include 'includes/system/segments/checkout/insert_history.php';

        $db->query("DELETE FROM customers_basket WHERE customers_id = " . (int)$customer_id);
        $db->query("DELETE FROM customers_basket_attributes WHERE customers_id = " . (int)$customer_id);
      } else {

        //error_log("order status {$check['orders_status']}" . ' ipn lost race');
      }
    }
  }
} catch (Exception $e) {
  error_log($e->getMessage());
}

Session::destroy();

require 'includes/application_bottom.php';
