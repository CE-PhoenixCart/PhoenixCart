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
  if ( defined('MODULE_PAYMENT_INSTALLED') && !Text::is_empty(MODULE_PAYMENT_INSTALLED) && in_array('braintree_cc.php', explode(';', MODULE_PAYMENT_INSTALLED)) ) {
    $braintree_cc = new braintree_cc();

    if ( !$braintree_cc->enabled ) {
      Href::redirect($account_link);
    }
  } else {
    Href::redirect($account_link);
  }

  $braintree_cards = new cm_account_braintree_cards();

  if ( !$braintree_cards->isEnabled() ) {
    Href::redirect($account_link);
  }

  $cards_link = $Linker->build();
  if ( isset($_GET['action']) ) {
    if ( Form::validate_action_is('delete') && is_numeric($_GET['id'] ?? '')) {
      $token_query = $db->query("SELECT id, braintree_token FROM customers_braintree_tokens WHERE id = " . (int)$_GET['id'] . " AND customers_id = " . (int)$_SESSION['customer_id']);

      if ($token = $token_query->fetch_assoc()) {
        $braintree_cc->deleteCard($token['braintree_token'], $token['id']);

        $messageStack->add_session('cards', MODULE_CONTENT_ACCOUNT_BRAINTREE_CARDS_SUCCESS_DELETED, 'success');
      }
    }

    Href::redirect($cards_link);
  }

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
