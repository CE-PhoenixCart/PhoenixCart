<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  chdir('../../../../');
  require 'includes/application_top.php';

// if the customer is not logged on, redirect them to the login page
  $parameters = [
    'page' => 'checkout_payment.php',
  ];
  $hooks->register_pipeline('loginRequired', $parameters);

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    Href::redirect($Linker->build('shopping_cart.php'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if ((isset($_SESSION['cart']->cartID, $_SESSION['cartID']) && $_SESSION['cart']->cartID !== $_SESSION['cartID'])) {
    Href::redirect($Linker->build('checkout_shipping.php'));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!isset($_SESSION['shipping'])) {
    Href::redirect($Linker->build('checkout_shipping.php'));
  }

  if (!isset($_SESSION['payment'])
    || (($_SESSION['payment'] !== 'sage_pay_direct') && ($_SESSION['payment'] !== 'sage_pay_server'))
    || (($_SESSION['payment'] === 'sage_pay_server') && !isset($_SESSION['sage_pay_server_nexturl'])))
  {
    Href::redirect($Linker->build('checkout_payment.php'));
  }

// load the selected payment module
  $payment_modules = new payment($_SESSION['payment']);

  $order = new order();

  $payment_modules->update_status();

  if ( ( is_array($payment_modules->modules) && (count($payment_modules->modules) > 1) && !is_object(${$_SESSION['payment']}) )
    || (is_object(${$_SESSION['payment']}) && (${$_SESSION['payment']}->enabled == false)) )
  {
    Href::redirect($Linker->build('checkout_payment.php', ['error_message' => ERROR_NO_PAYMENT_MODULE_SELECTED]));
  }

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

// load the selected shipping module
  $shipping_modules = new shipping($_SESSION['shipping']);

  $order_total_modules = new order_total;
  $order_total_modules->process();

  require DIR_FS_CATALOG . 'includes/system/segments/checkout/check_stock.php';

  require language::map_to_translation('checkout_confirmation.php');

  if ($_SESSION['payment'] == 'sage_pay_direct') {
    $iframe_url = $Linker->build('ext/modules/payment/sage_pay/direct_3dauth.php');
  } else {
    $iframe_url = $sage_pay_server_nexturl;
  }

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
