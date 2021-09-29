<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if ( !isset($_POST['customers_email_address'])) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
    return;
  }

  if (isset($_POST['back_x']) || !($mail_sent_to = phoenix_choose_audience($_POST['customers_email_address'])) ) {
    return;
  }

  switch ($_POST['customers_email_address']) {
    case '***':
      $mail_query = $db->query($customer_data->build_read(['name', 'email_address'], 'customers'));
      break;
    case '**D':
      $mail_query = $db->query($customer_data->build_read(['name', 'email_address'], 'customers', ['newsletter' => true]));
      break;
    default:
      $mail_query = $db->query($customer_data->build_read(
        ['name', 'email_address'],
        'customers',
        ['email_address' => Text::input($_POST['customers_email_address'])]));
      break;
  }

  $from_name = Text::input($_POST['from_name']);
  $from_address = Text::input($_POST['from_address']);
  $subject = Text::input($_POST['subject']);
  $message = trim($_POST['message']);

// Use the email class directly to ease sending  to multiple recipients
  $mimemessage = new email();
  $mimemessage->add_message($message);
  $mimemessage->build_message();

  $count = 0;
  while ($mail = $mail_query->fetch_assoc()) {
    if ($mimemessage->send($customer_data->get('name', $mail), $customer_data->get('email_address', $mail), $from_name, $from_address, $subject)) {
      $count++;
    }
  }

  $admin_hooks->cat('sendEmailToUserAction');
  if ($count > 0) {
    $messageStack->add_session(sprintf(NOTICE_EMAIL_SENT_TO, $mail_sent_to), 'success');
  }
  Href::redirect($Admin->link('mail.php'));
