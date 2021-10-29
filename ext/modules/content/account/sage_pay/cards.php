<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  chdir('../../../../../');
  require 'includes/application_top.php';

  $hooks->register_pipeline('loginRequired');

  $account_link = $Linker->build('account.php');
  if ( defined('MODULE_PAYMENT_INSTALLED') && !Text::is_empty(MODULE_PAYMENT_INSTALLED) && in_array('sage_pay_direct.php', explode(';', MODULE_PAYMENT_INSTALLED)) ) {
    $sage_pay_direct = new sage_pay_direct();

    if ( !$sage_pay_direct->enabled ) {
      Href::redirect($account_link);
    }
  } else {
    Href::redirect($account_link);
  }

  $sage_pay_cards = new cm_account_sage_pay_cards();

  if ( !$sage_pay_cards->isEnabled() ) {
    Href::redirect($account_link);
  }

  $cards_link = $Linker->build();
  if ( isset($_GET['action']) ) {
    if (Form::validate_action_is('delete') && is_numeric($_GET['id'] ?? '')) {
      $token_query = $db->query("SELECT id, sagepay_token FROM customers_sagepay_tokens WHERE id = " . (int)$_GET['id'] . " AND customers_id = " . (int)$_SESSION['customer_id']);

      if ($token = $token_query->fetch_assoc()) {
        $sage_pay_direct->deleteCard($token['sagepay_token'], $token['id']);

        $messageStack->add_session('cards', MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_SUCCESS_DELETED, 'success');
      }
    }

    Href::redirect($cards_link);
  }

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
