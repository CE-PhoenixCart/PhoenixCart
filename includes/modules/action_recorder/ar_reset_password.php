<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ar_reset_password extends abstract_action_recorder {

    const CONFIG_KEY_BASE = 'MODULE_ACTION_RECORDER_RESET_PASSWORD_';

    public function canPerform($user_id, $user_name) {
      $check_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT id
 FROM action_recorder
 WHERE module = '%s' AND user_name = '%s' AND date_added >= DATE_SUB(NOW(), INTERVAL %d MINUTE) AND success = 1
 ORDER BY date_added DESC
 LIMIT %d
EOSQL
        , $GLOBALS['db']->escape($this->code), $GLOBALS['db']->escape($user_name), (int)$this->minutes, (int)$this->attempts));

      return mysqli_num_rows($check_query) < $this->attempts;
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'MINUTES' => [
          'title' => 'Allowed Minutes',
          'value' => '5',
          'desc' => 'Number of minutes to allow password resets to occur.',
        ],
        $this->config_key_base . 'ATTEMPTS' => [
          'title' => 'Allowed Attempts',
          'value' => '1',
          'desc' => 'Number of password reset attempts to allow within the specified period.',
        ],
      ];
    }

  }
