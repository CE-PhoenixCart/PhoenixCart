<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $hooks->register_pipeline('loginRequired');

  if (!$customer_data->has(['newsletter'])) {
    Href::redirect($Linker->build('account.php'));
  }

// needs to be included earlier to set the success message in the messageStack
  require language::map_to_translation('account_newsletters.php');

  $customer_data->build_read(['newsletter'], 'customers', ['id' => (int)$_SESSION['customer_id']]);
  $newsletter_query = $db->query($customer_data->build_read(['newsletter'], 'customers', ['id' => (int)$_SESSION['customer_id']]));
  $newsletter = $newsletter_query->fetch_assoc();

  if (Form::validate_action_is('process')) {
    if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
      $newsletter_general = Text::input($_POST['newsletter_general']);
    } else {
      $newsletter_general = 0;
    }

    $saved_newsletter = $customer_data->get('newsletter', $newsletter);
    if ($newsletter_general != $saved_newsletter) {
      $customer_data->update(['newsletter' => (int)(('1' == $saved_newsletter) ? 0 : 1)], ['id' => (int)$_SESSION['customer_id']]);
    }

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    Href::redirect($Linker->build('account.php'));
  }

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
