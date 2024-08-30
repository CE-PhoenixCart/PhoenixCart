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

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function login() {
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

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    public function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-6 mb-4',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '1000',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
