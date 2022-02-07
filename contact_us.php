<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require language::map_to_translation('contact_us.php');

  if (Form::validate_action_is('send')) {
    $error = false;

    $name = Text::input($_POST['name']);
    $email_address = Text::input($_POST['email']);
    $enquiry = Text::input($_POST['enquiry']);

    $email_class = $customer_data->has('email_address')
                 ? get_class($customer_data->get_module('email_address'))
                 : 'cd_email_address';

    if (!$email_class::validate($email_address)) {
      Form::block_processing();

      $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $actionRecorder = new actionRecorder('ar_contact_us', ($_SESSION['customer_id'] ?? null), $name);
    if (!$actionRecorder->canPerform()) {
      Form::block_processing();

      $actionRecorder->record(false);

      $messageStack->add('contact', sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES') ? (int)MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES : 15)));
    }

    $hooks->cat('injectFormVerify');

    if (Form::is_valid()) {
      Notifications::mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, sprintf(EMAIL_SUBJECT, STORE_NAME), $enquiry, $name, $email_address);

      $actionRecorder->record();

      Href::redirect($Linker->build('contact_us.php', ['action' => 'success']));
    }
  }

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
