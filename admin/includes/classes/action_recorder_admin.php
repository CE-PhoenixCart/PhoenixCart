<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class actionRecorderAdmin extends actionRecorder {

    public function __construct($module, $user_id = null, $user_name = null) {
      $module = Text::sanitize(str_replace(' ', '', $module));

      if (!defined('MODULE_ACTION_RECORDER_INSTALLED')
        || Text::is_empty(MODULE_ACTION_RECORDER_INSTALLED)
        || Text::is_empty($module)
        || !in_array("$module.php", explode(';', MODULE_ACTION_RECORDER_INSTALLED))
        || !class_exists($module))
      {
        return false;
      }

      $this->_module = $module;

      if (!empty($user_id) && is_numeric($user_id)) {
        $this->_user_id = $user_id;
      }

      if (!empty($user_name)) {
        $this->_user_name = $user_name;
      }

      $GLOBALS[$this->_module] = new $module();
      $GLOBALS[$this->_module]->setIdentifier();
    }

    public static function notify_expiration() {
      $GLOBALS['messageStack']->add_session(
        sprintf(SUCCESS_EXPIRED_ENTRIES, $GLOBALS['expired_entries']),
        'success');
    }

  }
