<?php
/*
* $Id: cards.php
* $Loc: /ext/modules/content/account/stripe_sca/
*
* Name: StripeSCA
* Version: 1.70
* Release Date: 2025-03-02
* Author: Rainer Schmied
* 	 phoenixcartaddonsaddons.com / raiwa@phoenixcartaddons.com
*
* License: Released under the GNU General Public License
*
* Comments: Author: [Rainer Schmied @raiwa]
* Author URI: [www.phoenixcartaddons.com]
* 
* CE Phoenix, E-Commerce made Easy
* https://phoenixcart.org
* 
* Copyright (c) 2021 Phoenix Cart
* 
* 
*/

  chdir('../../../../../');
  require('includes/application_top.php');

  $hooks->register_pipeline('loginRequired');

  $account_link = $Linker->build('account.php');
  if ( defined('MODULE_PAYMENT_INSTALLED') && !Text::is_empty(MODULE_PAYMENT_INSTALLED) && in_array('stripe_sca.php', explode(';', MODULE_PAYMENT_INSTALLED)) ) {
    $stripe_sca = new stripe_sca();

    if ( !$stripe_sca->enabled ) {
      Href::redirect($account_link);
    }
  } else {
    Href::redirect($account_link);
  }

  $stripe_cards = new cm_account_stripe_sca_cards();

  if ( !$stripe_cards->isEnabled() ) {
      Href::redirect($account_link);
  }

  $cards_link = $Linker->build();
  if ( isset($_GET['action']) ) {
    if (Form::validate_action_is('delete', 2) && is_numeric($_GET['id'] ?? '')) {
      $token_query = $db->query(sprintf(<<<'EOSQL'
SELECT id, stripe_token 
  FROM customers_stripe_tokens 
  WHERE id = %s
    AND customers_id = %s
EOSQL
            , (int)$_GET['id'], (int)$_SESSION['customer_id']));

      if ($token = $token_query->fetch_assoc()) {

        list($customer, $card) = explode(':|:', $token['stripe_token'], 2);

        $stripe_sca->deleteCard($card, $customer, $token['id']);

        $messageStack->add_session('cards', MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_SUCCESS_DELETED, 'success');
      }
    }

    Href::redirect($cards_link);
  }

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
