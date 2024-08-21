<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_personal_details extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_PERSONAL_DETAILS_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $port_my_data, $customer;

      $gdpr_fname = $port_my_data['YOU']['PERSONAL']['FNAME'] = $customer->get('firstname');
      $gdpr_lname = $port_my_data['YOU']['PERSONAL']['LNAME'] = $customer->get('lastname');
      switch ($customer->get('gender')) {
        case 'm':
          $gdpr_gender = MODULE_CONTENT_GDPR_PERSONAL_DETAILS_GENDER_M;
          break;
        case 'f':
          $gdpr_gender = MODULE_CONTENT_GDPR_PERSONAL_DETAILS_GENDER_F;
          break;
        default:
          $gdpr_gender = MODULE_CONTENT_GDPR_PERSONAL_DETAILS_UNKNOWN;
      }
      $port_my_data['YOU']['PERSONAL']['GENDER'] = $gdpr_gender;

      $gdpr_dob = (empty($customer->get('dob'))) ? MODULE_CONTENT_GDPR_PERSONAL_DETAILS_UNKNOWN : Date::abridge($customer->get('dob'));

      $port_my_data['YOU']['PERSONAL']['DOB'] = $gdpr_dob;

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
          'value' => '100',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
