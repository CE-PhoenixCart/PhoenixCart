<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_site_details extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_SITE_DETAILS_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $port_my_data, $customer;

      $r_data = $GLOBALS['db']->query("SELECT COUNT(*) AS reviews_count FROM reviews WHERE customers_id = " . (int)$_SESSION['customer_id'])->fetch_assoc();
      $pn_data = $GLOBALS['db']->query("SELECT COUNT(*) AS notifications_count FROM products_notifications WHERE customers_id = " . (int)$_SESSION['customer_id'])->fetch_assoc();

      $gdpr_newsletter = (1 == $customer->get('customers_newsletter'))
                       ? MODULE_CONTENT_GDPR_SITE_DETAILS_NEWSLETTER_SUB_YES
                       : MODULE_CONTENT_GDPR_SITE_DETAILS_NEWSLETTER_SUB_NO;

      $port_my_data['YOU']['SITE']['NEWSLETTER'] = $gdpr_newsletter;
      $port_my_data['YOU']['SITE']['ACCOUNTCREATED'] = $customer->get('date_account_created');
      $port_my_data['YOU']['SITE']['LOGONS']['COUNT'] = max($customer->get('number_of_logons'), 1);
      $port_my_data['YOU']['SITE']['LOGONS']['MOSTRECENT'] = $customer->get('date_last_logon') ?? $customer->get('date_account_created');
      $port_my_data['YOU']['REVIEW']['COUNT'] = $r_data['reviews_count'];
      $port_my_data['YOU']['NOTIFICATION']['COUNT'] = $pn_data['notifications_count'];

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
