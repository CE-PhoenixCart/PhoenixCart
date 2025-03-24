<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Ajax action to record comments on order record
  Paypal Standard Payments
  Basic Paypal Payment Module for Phoenix Cart
  More sophisticated Paypal integration available at https://phoenixcart.org/forum/addons/

  author: John Ferguson @BrockleyJohn phoenix@cartmart.uk

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

chdir('../../../../');
require 'includes/application_top.php';

require 'includes/system/segments/checkout/pipeline.php';

$payload = @file_get_contents('php://input');

header('Content-Type: application/json');
if (strlen($payload)) {

  $paypal_standard = new paypal_standard();

  if ($paypal_standard->isEnabled()) {

    try {

      $indata = json_decode($payload, true);

      if (empty($indata['cartid']) || $indata['cartid'] != $_SESSION['cartID']) {
        echo json_encode(['error' => 'Invalid cartID']);
        exit;
      }

      if ($order_id = $paypal_standard->order_comments($indata)) {
        echo json_encode(['result' => 'Comment recorded for ' . $order_id, 'orderid' => $order_id]);
      } else {
        echo json_encode(['error' => 'Failed to update order']);
      }
      exit;

    } catch (Exception $e) {
      echo json_encode(['error' => 'Invalid payload']);
      exit;
    }

  }

} else {
  echo json_encode(['error' => 'No payload']);
}
