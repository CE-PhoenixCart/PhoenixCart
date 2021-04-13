<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_login_form extends abstract_executable_module {

    const REQUIRES = [ 'password', 'id', 'email_address' ];

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_LOGIN_FORM_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    function login() {
      global $customer_data;

      $email_address = Text::input($_POST['email_address']);

      $customer_query = $GLOBALS['db']->query($customer_data->build_read(['id', 'password'], 'customers', ['email_address' => $email_address]) . ' LIMIT 1');
      $customer_details = $customer_query->fetch_assoc();
      if (!$customer_details) {
        return false;
      }

      $password = Text::input($_POST['password']);
      if (!Password::validate($password, $customer_data->get('password', $customer_details))) {
        return false;
      }

// set $login_customer_id globally and perform post login code in catalog/login.php
      $GLOBALS['login_customer_id'] = (int)$customer_data->get('id', $customer_details);

// if stored under an older password hashing method, save with the current method
      if (Password::needs_rehash($customer_data->get('password', $customer_details))) {
        $customer_data->update(['password' => $password], ['id' => (int)$GLOBALS['login_customer_id']], 'customers');
      }

      return true;
    }

    public function execute() {
      if ((Form::validate_action_is('process')) && (!$this->login())) {
        $GLOBALS['messageStack']->add('login', MODULE_CONTENT_LOGIN_TEXT_LOGIN_ERROR);
      }

      $content_width = (int)MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH;

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    public function get_parameters() {
      return [
        'MODULE_CONTENT_LOGIN_FORM_STATUS' => [
          'title' => 'Enable Login Form Module',
          'value' => 'True',
          'desc' => 'Do you want to enable the login form module?',
          'set_func' => "tep_cfg_select_option(['True', 'False'], ",
        ],
        'MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '6',
          'desc' => 'What width container should the content be shown in? (12 = full width, 6 = half width).',
          'set_func' => "tep_cfg_select_option(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_LOGIN_FORM_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '1000',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
