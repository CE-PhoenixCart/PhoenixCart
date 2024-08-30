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
    }

    public function execute() {
      global $port_my_data, $customer;

      $port_my_data['YOU']['CONTACT']['EMAIL'] = $customer->get('email_address');
      $port_my_data['YOU']['CONTACT']['PHONE'] = $customer->get('telephone') ?: MODULE_CONTENT_GDPR_CONTACT_DETAILS_UNKNOWN;

      $port_my_data['YOU']['CONTACT']['FAX'] = $customer->get('fax') ?: MODULE_CONTENT_GDPR_CONTACT_DETAILS_UNKNOWN;

      $port_my_data['YOU']['CONTACT']['ADDRESS']['MAIN']['COUNT'] = 1;
      $port_my_data['YOU']['CONTACT']['ADDRESS']['MAIN']['LIST'][1] = $customer->make_address_label($customer->get('default_address_id'), true, ' ', ', ');

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
          'value' => '125',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
