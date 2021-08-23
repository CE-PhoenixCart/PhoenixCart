<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ar_admin_login extends abstract_action_recorder {

    const CONFIG_KEY_BASE = 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_';

    function canPerform($user_id, $user_name) {
      $check_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT id
 FROM action_recorder
 WHERE module = '%s' AND (user_name = '%s' OR identifier = '%s') AND date_added >= DATE_SUB(NOW(), INTERVAL %d MINUTE) AND success = 0
 ORDER BY date_added DESC
 LIMIT %d
EOSQL
        , $GLOBALS['db']->escape($this->code),
        $GLOBALS['db']->escape($user_name),
        $GLOBALS['db']->escape($this->identifier),
        (int)$this->minutes,
        (int)$this->attempts));

      return mysqli_num_rows($check_query) < $this->attempts;
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'MINUTES' => [
          'title' => 'Allowed Minutes',
          'value' => '5',
          'desc' => 'Number of minutes to allow login attempts to occur.',
        ],
        $this->config_key_base . 'ATTEMPTS' => [
          'title' => 'Allowed Attempts',
          'value' => '3',
          'desc' => 'Number of login attempts to allow within the specified period.',
        ],
      ];
    }

  }
