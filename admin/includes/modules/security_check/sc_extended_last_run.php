<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class sc_extended_last_run {

    public $type = 'warning';

    public function pass() {
      if ( Request::get_page() === 'security_checks.php' ) {
        if ( defined('MODULE_SECURITY_CHECK_EXTENDED_LAST_RUN_DATETIME') ) {
          $GLOBALS['db']->query("UPDATE configuration SET configuration_value = '" . time() . "' WHERE configuration_key = 'MODULE_SECURITY_CHECK_EXTENDED_LAST_RUN_DATETIME'");
        } else {
          $GLOBALS['db']->query(sprintf(<<<'EOSQL'
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, date_added)
  VALUES ('Security Check Extended Last Run', 'MODULE_SECURITY_CHECK_EXTENDED_LAST_RUN_DATETIME', '%d', 'The date and time the last extended security check was performed.', 6, NOW())
EOSQL
            , time()));
        }

        return true;
      }

      return defined('MODULE_SECURITY_CHECK_EXTENDED_LAST_RUN_DATETIME') && (MODULE_SECURITY_CHECK_EXTENDED_LAST_RUN_DATETIME > strtotime('-30 days'));
    }

    public function get_message() {
      return '<a href="' . $GLOBALS['Admin']->link('security_checks.php') . '">' . MODULE_SECURITY_CHECK_EXTENDED_LAST_RUN_OLD . '</a>';
    }

  }
