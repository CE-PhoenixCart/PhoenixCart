<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  abstract class abstract_action_recorder extends abstract_module {

    public $minutes;
    public $attempts;
    public $identifier;

    public function __construct() {
      if (is_null($this->config_key_base)) {
        $this->config_key_base = static::CONFIG_KEY_BASE;
      }

      $this->code = get_class($this);
      $this->title = static::get_constant(static::CONFIG_KEY_BASE . 'TEXT_TITLE')
                  ?? static::get_constant(static::CONFIG_KEY_BASE . 'TITLE');
      $this->description = static::get_constant(static::CONFIG_KEY_BASE . 'TEXT_DESCRIPTION')
                        ?? static::get_constant(static::CONFIG_KEY_BASE . 'DESCRIPTION');

      $this->status_key = $this->config_key_base . 'MINUTES';
      if (defined($this->status_key)) {
        $this->enabled = constant($this->status_key) > 0;
        $this->minutes = $this->base_constant('MINUTES');
        $this->attempts = $this->base_constant('ATTEMPTS');
      }
    }

    public function setIdentifier() {
      $this->identifier = Request::get_ip();
    }

    public function expireEntries() {
      $GLOBALS['db']->query("DELETE FROM action_recorder WHERE module = '" . $this->code . "' AND date_added < DATE_SUB(NOW(), INTERVAL " . (int)$this->minutes  . " MINUTE)");

      return mysqli_affected_rows($GLOBALS['db']);
    }

  }
