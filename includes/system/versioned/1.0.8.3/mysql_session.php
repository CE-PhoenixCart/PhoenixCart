<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class mysql_session extends SessionHandler implements SessionHandlerInterface, SessionUpdateTimestampHandlerInterface {

    public function close() {
      return true;
    }

    public function destroy($key) {
      return false !== $GLOBALS['db']->query("DELETE FROM sessions WHERE sesskey = '" . $GLOBALS['db']->escape($key) . "'");
    }

    public function gc($maxlifetime) {
      return false !== $GLOBALS['db']->query("DELETE FROM sessions WHERE expiry < '" . (int)(time() - $maxlifetime) . "'");
    }

    public function open($save_path, $session_name) {
      return true;
    }

    public function read($key) {
      $value_query = $GLOBALS['db']->query("SELECT value FROM sessions WHERE sesskey = '" . $GLOBALS['db']->escape($key) . "'");
      $value = $value_query->fetch_assoc();

      return $value['value'] ?? '';
    }

    public function updateTimestamp($key, $ignore) {
      return false !== $GLOBALS['db']->query("UPDATE sessions SET expiry = " . (int)time() . " WHERE sesskey = '" . $GLOBALS['db']->escape($key) . "'");
    }

    public function validateId($key) {
      $query = $GLOBALS['db']->query("SELECT expiry FROM sessions WHERE sesskey = '" . $GLOBALS['db']->escape($key) . "'");
      return mysqli_num_rows($query) > 0;
    }

    public function write($key, $value) {
      return false !== $GLOBALS['db']->query("INSERT INTO sessions (sesskey, expiry, value) VALUES ('"
        . $GLOBALS['db']->escape($key) . "', " . (int)time() . ", '" . $GLOBALS['db']->escape($value)
        . "') ON DUPLICATE KEY UPDATE expiry = VALUES(expiry), value = VALUES(value)");
    }

  }
