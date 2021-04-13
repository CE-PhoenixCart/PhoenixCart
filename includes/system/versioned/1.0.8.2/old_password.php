<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class old_password {

////
// This function validates a plain text password with a
// salted password
    public static function validate($plain, $encrypted) {
      if (('' === $plain) || ('' === $encrypted)) {
        return false;
      }

// split apart the hash / salt
      $stack = explode(':', $encrypted);

      return (hash_equals($stack[0], md5($stack[1] . $plain)) && (count($stack) === 2));
    }

  }
