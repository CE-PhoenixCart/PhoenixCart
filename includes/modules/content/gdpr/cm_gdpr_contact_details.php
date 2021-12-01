<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_contact_details extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_CONTACT_DETAILS_';

    public function __construct() {
      parent::__construct(__FILE__);
      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
    }

    public function execute() {
      global $port_my_data, $customer;

      $port_my_data['YOU']['CONTACT']['EMAIL'] = $customer->get('email_address');
      $port_my_data['YOU']['CONTACT']['PHONE'] = $customer->get('telephone');

      $port_my_data['YOU']['CONTACT']['FAX'] = $customer->get('fax') ?: MODULE_CONTENT_GDPR_CONTACT_DETAILS_UNKNOWN;

      $port_my_data['YOU']['CONTACT']['ADDRESS']['MAIN']['COUNT'] = 1;
      $port_my_data['YOU']['CONTACT']['ADDRESS']['MAIN']['LIST'][1] = $customer->make_address_label($customer->get('default_address_id'), true, ' ', ', ');

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_GDPR_CONTACT_DETAILS_STATUS' => [
          'title' => 'Enable Contact Details Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the GDPR page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_GDPR_CONTACT_DETAILS_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_GDPR_CONTACT_DETAILS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '125',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
