<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ar_contact_us extends abstract_action_recorder {

    const CONFIG_KEY_BASE = 'MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_';

    public function canPerform($user_id, $user_name) {
      $check_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT date_added
 FROM action_recorder
 WHERE module = '%s' AND (%sidentifier = '%s') AND date_added >= DATE_SUB(NOW(), INTERVAL %d MINUTE) AND success = 1
 ORDER BY date_added DESC
 LIMIT 1
EOSQL
        , $GLOBALS['db']->escape($this->code),
        empty($user_id) ? '' : "user_id = " . (int)$user_id . " OR ",
        $GLOBALS['db']->escape($this->identifier),
        (int)$this->minutes));

      return !mysqli_num_rows($check_query);
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'MINUTES' => [
          'title' => 'Minimum Minutes Per E-Mail',
          'value' => '15',
          'desc' => 'Minimum number of minutes to allow 1 e-mail to be sent (eg, 15 for 1 e-mail every 15 minutes)',
        ],
      ];
    }

  }
