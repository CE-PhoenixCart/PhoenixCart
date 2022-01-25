<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require language::map_to_translation('password_forgotten.php');

  if (!$customer_data->has(['email_address'])) {
    Href::redirect($Linker->build('index.php'));
  }

  $password_reset_initiated = false;

  if (Form::validate_action_is('process')) {
    $email_address = Text::input($_POST['email_address']);

    $check_customer_query = $db->query($customer_data->build_read(['name', 'id'], 'customers', ['email_address' => $email_address]));
    if ($check_customer = $check_customer_query->fetch_assoc()) {
      $actionRecorder = new actionRecorder('ar_reset_password', $customer_data->get('id', $check_customer), $email_address);

      if ($actionRecorder->canPerform()) {
        $actionRecorder->record();

        $reset_key = Password::create_random(40);

        $db->query("UPDATE customers_info SET password_reset_key = '" . $db->escape($reset_key) . "', password_reset_date = NOW() WHERE customers_info_id = " . (int)$check_customer['id']);

        $reset_key_url = $Linker->build('password_reset.php', ['account' => $email_address, 'key' => $reset_key], false);

        if ( strpos($reset_key_url, '&amp;') !== false ) {
          $reset_key_url = str_replace('&amp;', '&', $reset_key_url);
        }

        Notifications::mail(
          $customer_data->get('name', $check_customer),
          $email_address,
          EMAIL_PASSWORD_RESET_SUBJECT,
          sprintf(EMAIL_PASSWORD_RESET_BODY, $reset_key_url),
          STORE_OWNER,
          STORE_OWNER_EMAIL_ADDRESS);

        $password_reset_initiated = true;
      } else {
        $actionRecorder->record(false);

        $messageStack->add('password_forgotten', sprintf(ERROR_ACTION_RECORDER, $ar_reset_password->minutes));
      }
    } else {
      $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
    }
  }

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
