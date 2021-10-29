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

  if ( isset($_GET['payment_error']) && !Text::is_empty($_GET['payment_error']) ) {
    $redirect_url = $Linker->build('checkout_payment.php', ['payment_error' => $_GET['payment_error']]);
    if (isset($_GET['error']) && !Text::is_empty($_GET['error'])) {
      $redirect_url->set_parameter('error', $_GET['error']);
    }
    $form = new Form('redirect', $redirect_url, 'post', ['target' => '_top']);
  } else {
    if ('sage_pay_direct' === $_SESSION['payment']) {
      $form = new Form('redirect', $Linker->build('checkout_process.php', ['check' => '3D']), 'post', ['target' => '_top']);
      $form->hide('MD', $_POST['MD'])->hide('PaRes', $_POST['PaRes']);
    } else {
      $form = new Form('redirect', $Linker->build('checkout_process.php'), 'post', ['target' => '_top']);
    }
  }

  require language::map_to_translation('checkout_confirmation.php');
  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
