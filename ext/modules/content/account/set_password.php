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

  if (!isset($_SESSION['customer_id'])) {
// don't use loginRequired pipeline, as we don't want to snapshot to return later
    Href::redirect($Linker->build('login.php'));
  }

  if ( MODULE_CONTENT_ACCOUNT_SET_PASSWORD_ALLOW_PASSWORD != 'True' ) {
    Href::redirect($Linker->build('account.php'));
  }

  if (!$customer_data->has(['password'])) {
    Href::redirect($Linker->build('account.php'));
  }

  $check_customer = $db->query($customer_data->build_read(['password'], 'both', ['id' => (int)$_SESSION['customer_id']]))->fetch_assoc();

  // only allow to set the password when it is blank
  if ( !empty($customer_data->get('password', $check_customer)) ) {
    Href::redirect($Linker->build('account.php'));
  }

// needs to be included earlier to set the success message in the messageStack
  require language::map_to_translation('modules/content/account/cm_account_set_password.php');

  $page_fields = ['password', 'password_confirmation'];

  if (Form::validate_action_is('process')) {
    $customer_details = $customer_data->process($page_fields);

    if (Form::is_valid()) {
      $customer_data->update(['password' => $customer_data->get('password', $customer_details)], ['id' => (int)$_SESSION['customer_id']]);

      $db->query("UPDATE customers_info SET customers_info_date_account_last_modified = NOW() WHERE customers_info_id = " . (int)$_SESSION['customer_id']);

      $messageStack->add_session('account', MODULE_CONTENT_ACCOUNT_SET_PASSWORD_SUCCESS_PASSWORD_SET, 'success');

      Href::redirect($Linker->build('account.php'));
    }
  }

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
