<?php
/*
* $Id: cm_account_stripe_sca_cards.php
* $Loc: /includes/modules/content/account/
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

  class cm_account_stripe_sca_cards extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_';
	public $public_title = '';

    public function __construct() {
      parent::__construct(__FILE__);

      $stripe_enabled = false;

      if ( defined('MODULE_PAYMENT_INSTALLED')
        && !Text::is_empty(MODULE_PAYMENT_INSTALLED)
        && in_array('stripe_sca.php', explode(';', MODULE_PAYMENT_INSTALLED)) )
      {
        $stripe_sca = new stripe_sca();

        if ( $stripe_sca->enabled ) {
          $stripe_enabled = true;

          $this->public_title = MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_TITLE;

          if ( MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Test' ) {
            $this->title .= ' [Test]';
            $this->public_title .= ' (' . $stripe_sca->code . '; Test)';
          }
        }
      }

      if ( $stripe_enabled !== true ) {
        $this->enabled = false;

        $this->description = '<div class="alert alert-warning">' . MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_ERROR_MAIN_MODULE . '</div>' . $this->description;
      }
    }

    public function execute() {
      $GLOBALS['Template']->_data['account']['account']['links']['stripe_sca_cards'] = [
        'title' => $this->public_title,
        'link' => $GLOBALS['Linker']->build('ext/modules/content/account/stripe_sca/cards.php'),
        'icon' => 'fab fa-cc-stripe fa-5x',
      ];
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_STATUS' => [
          'title' => 'Enable Stripe Card Management',
          'value' => 'True',
          'desc' => 'Do you want to enable the Stripe Card Management module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_ACCOUNT_STRIPE_SCA_CARDS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
