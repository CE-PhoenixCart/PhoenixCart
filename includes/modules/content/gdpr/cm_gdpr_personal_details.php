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
      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
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

      $bad_dates = ['0000-00-00 00:00:00', '1970-01-01 00:00:01'];
      $gdpr_dob = (in_array($customer->get('dob'), $bad_dates))
                ? MODULE_CONTENT_GDPR_PERSONAL_DETAILS_UNKNOWN
                : Date::abridge($customer->get('dob'));
      $port_my_data['YOU']['PERSONAL']['DOB'] = $gdpr_dob;

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_GDPR_PERSONAL_DETAILS_STATUS' => [
          'title' => 'Enable Personal Details Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the GDPR page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_GDPR_PERSONAL_DETAILS_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_GDPR_PERSONAL_DETAILS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '100',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
