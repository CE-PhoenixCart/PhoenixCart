<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_mailchimp_360 extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_MAILCHIMP_360_';

    protected $group = 'header_tags';

    public function execute() {
      include 'includes/apps/mailchimp_360/MCAPI.class.php';
      include 'includes/apps/mailchimp_360/mc360.php';

      $mc360 = new mc360();
      $mc360->set_cookies();

      if (basename(Request::get_page()) === 'checkout_success.php') {
        $mc360->process();
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_MAILCHIMP_360_STATUS' => [
          'title' => 'Enable MailChimp 360 Module',
          'value' => 'True',
          'desc' => 'Do you want to activate this module in your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_MAILCHIMP_360_API_KEY' => [
          'title' => 'API Key',
          'value' => '',
          'desc' => 'An API Key assigned to your MailChimp account',
        ],
        'MODULE_HEADER_TAGS_MAILCHIMP_360_DEBUG_EMAIL' => [
          'title' => 'Debug E-Mail',
          'value' => '',
          'desc' => 'If an e-mail address is entered, debug data will be sent to it',
        ],
        'MODULE_HEADER_TAGS_MAILCHIMP_360_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        'MODULE_HEADER_TAGS_MAILCHIMP_360_STORE_ID' => [
          'title' => 'MailChimp Store ID',
          'value' => '',
          'desc' => 'Do not edit. Store ID value.',
        ],
        'MODULE_HEADER_TAGS_MAILCHIMP_360_KEY_VALID' => [
          'title' => 'MailChimp Key Valid',
          'value' => '',
          'desc' => 'Do not edit. Key Value value.',
        ],
      ];
    }

  }
