<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

// no reason to be on this page if the requirements not installed
  if (!$customer_data->has(['email_address', 'password', 'password_reset_key', 'password_reset_date'])) {
    Href::redirect($Linker->build('index.php'));
  }

  require language::map_to_translation('password_reset.php');

  $page_fields = [ 'password', 'password_confirmation' ];

  $error = false;

  if (isset($_GET['account']) && isset($_GET['key'])) {
    $email_address = Text::input($_GET['account']);
    $password_key = Text::input($_GET['key']);

    $email_class = get_class($customer_data->get_module('email_address'));

    if ( (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) || !$email_class::validate($email_address) ) {
      $error = true;

      $messageStack->add_session('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
    } elseif (strlen($password_key) != 40) {
      $error = true;

      $messageStack->add_session('password_forgotten', TEXT_NO_RESET_LINK_FOUND);
    } else {
      $check_customer_query = $db->query($customer_data->build_read(['id', 'email_address', 'password_reset_key', 'password_reset_date'], 'customers', ['email_address' => $email_address]));
      if ($check_customer = $check_customer_query->fetch_assoc()) {
        if ( empty($check_customer['password_reset_key']) || ($check_customer['password_reset_key'] != $password_key) || (strtotime($check_customer['password_reset_date'] . ' +1 day') <= time()) ) {
          $error = true;

          $messageStack->add_session('password_forgotten', TEXT_NO_RESET_LINK_FOUND);
        }
      } else {
        $error = true;

        $messageStack->add_session('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
      }
    }
  } else {
    $error = true;

    $messageStack->add_session('password_forgotten', TEXT_NO_RESET_LINK_FOUND);
  }

  if ($error) {
    Href::redirect($Linker->build('password_forgotten.php'));
  }

  if (Form::validate_action_is('process')) {
    $customer_details = $customer_data->process($page_fields);

    if (Form::is_valid()) {
      $customer_data->update(['password' => $customer_data->get('password', $customer_details)], ['id' => (int)$customer_data->get('id', $check_customer)]);

      $db->query("UPDATE customers_info SET customers_info_date_account_last_modified = NOW(), password_reset_key = NULL, password_reset_date = NULL WHERE customers_info_id = " . (int)$check_customer['customers_id']);

      $messageStack->add_session('login', SUCCESS_PASSWORD_RESET, 'success');

      Href::redirect($Linker->build('login.php'));
    }
  }

  require $Template->map(__FILE__, 'page');
  require 'includes/application_bottom.php';
