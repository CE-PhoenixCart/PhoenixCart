<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class actionRecorder {

    public $_module;
    public $_user_id;
    public $_user_name;

    public function __construct($module, $user_id = null, $user_name = null) {
      $module = Text::sanitize(str_replace(' ', '', $module));

      if (!defined('MODULE_ACTION_RECORDER_INSTALLED')
        || Text::is_empty(MODULE_ACTION_RECORDER_INSTALLED)
        || Text::is_empty($module)
        || !in_array("$module.php", explode(';', MODULE_ACTION_RECORDER_INSTALLED))
        || !class_exists($module))
      {
        return;
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

    public function canPerform() {
      if (!Text::is_empty($this->_module)) {
        return $GLOBALS[$this->_module]->canPerform($this->_user_id, $this->_user_name);
      }

      return false;
    }

    public function getTitle() {
      if (!Text::is_empty($this->_module)) {
        return $GLOBALS[$this->_module]->title;
      }
    }

    public function getIdentifier() {
      if (!Text::is_empty($this->_module)) {
        return $GLOBALS[$this->_module]->identifier;
      }
    }

    public function record($success = true) {
      if (!Text::is_empty($this->_module)) {
        $GLOBALS['db']->perform('action_recorder', [
          'module' => $this->_module,
          'user_id' => (int)$this->_user_id,
          'user_name' => $this->_user_name,
          'identifier' => $this->getIdentifier(),
          'success' => ($success ? 1 : 0),
          'date_added' => 'NOW()',
          ]);
      }
    }

    public function expireEntries() {
      if (!Text::is_empty($this->_module)) {
        return $GLOBALS[$this->_module]->expireEntries();
      }
    }
  }
