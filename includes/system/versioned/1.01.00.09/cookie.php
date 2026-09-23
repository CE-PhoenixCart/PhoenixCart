<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  class Cookie {

    public static function save_session_parameters($options = COOKIE_OPTIONS) {
      session_set_cookie_params($options);
    }

    public static function save($name, $value, $options = COOKIE_OPTIONS) {
      unset($options['lifetime']);
      if (!isset($options['expires'])) {
        $options['expires'] = strtotime('+1 month');
      }

      setcookie($name, $value, $options);
    }

  }
