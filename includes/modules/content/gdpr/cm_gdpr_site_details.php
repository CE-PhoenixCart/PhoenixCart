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
      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
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
      $port_my_data['YOU']['SITE']['LOGONS']['MOSTRECENT'] = $customer->get('date_of_last_logon') ?? $customer->get('date_account_created');
      $port_my_data['YOU']['REVIEW']['COUNT'] = $r_data['reviews_count'];
      $port_my_data['YOU']['NOTIFICATION']['COUNT'] = $pn_data['notifications_count'];

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_GDPR_SITE_DETAILS_STATUS' => [
          'title' => 'Enable Site Details Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the GDPR page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_GDPR_SITE_DETAILS_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_GDPR_SITE_DETAILS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
