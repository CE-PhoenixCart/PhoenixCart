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

  if (!isset($_SESSION['sage_pay_direct_acsurl'])) {
    Href::redirect($Linker->build('checkout_payment.php'));
  }

  if (!isset($_SESSION['payment']) || ($_SESSION['payment'] !== 'sage_pay_direct')) {
    Href::redirect($Linker->build('checkout_payment.php'));
  }

  require language::map_to_translation('checkout_confirmation.php');
  require language::map_to_translation('modules/payment/sage_pay_direct.php');

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
