<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  class n_password_forgotten extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_NOTIFICATIONS_PASSWORD_FORGOTTEN_';

    const TRIGGERS = [ 'password_forgotten' ];

    public function notify($data) {
      ob_start();
      include Guarantor::ensure_global('Template')->map(__FILE__);
      
      return Notifications::mail(
          $data['name'], 
          $data['email_address'], 
          MODULE_NOTIFICATIONS_PASSWORD_FORGOTTEN_TEXT_SUBJECT, 
          ob_get_clean(), 
          STORE_OWNER, 
          STORE_OWNER_EMAIL_ADDRESS
      );
    }

    protected function get_parameters() {
      return [
        static::CONFIG_KEY_BASE . 'STATUS' => [
          'title' => 'Enable Password Forgotten Notification module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
      ];
    }

  }
