<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Password {

    public static function validate($plain, $hashed) {
      if (('' === $plain) || ('' === $hashed)) {
        return false;
      }

      switch (static::type($hashed)) {
        case 'salt':
          return old_password::validate($plain, $hashed);
        case 'phpass':
          $hasher = new PasswordHash(10, true);
          return $hasher->CheckPassword($plain, $hashed);
      }

      return password_verify($plain, $hashed);
    }

    public static function get_algorithm() {
      return defined('PHOENIX_ENCRYPTION')
           ? PHOENIX_ENCRYPTION
           : PASSWORD_DEFAULT;
    }

    public static function hash($plain) {
      return defined('PHOENIX_PASSWORD_OPTIONS')
           ? password_hash($plain,
               static::get_algorithm(), PHOENIX_PASSWORD_OPTIONS)
           : password_hash($plain, static::get_algorithm());
    }

    protected static function _needs_rehash($hashed) {
      return defined('PHOENIX_PASSWORD_OPTIONS')
           ? password_needs_rehash($hashed,
               static::get_algorithm(),
               PHOENIX_PASSWORD_OPTIONS)
           : password_needs_rehash($hashed, static::get_algorithm());
    }

    public static function needs_rehash($hashed) {
      return (static::type($hashed) !== 'native')
          || static::_needs_rehash($hashed);
    }

    public static function type($hashed) {
      if (preg_match('{^[A-Za-z0-9]{32}:[A-Za-z0-9]{2}$}', $hashed) === 1) {
        return 'salt';
      }

      if (Text::is_prefixed_by($hashed, '$P$')) {
        return 'phpass';
      }

      return 'native';
    }

    public static function create_random($length, $type = 'mixed') {
      if ( !in_array($type, ['mixed', 'letters', 'digits']) ) {
        $type = 'mixed';
      }

      $base = '';

      if ( ($type === 'mixed') || ($type === 'letters') ) {
        $base .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      }

      if ( ($type === 'mixed') || ($type === 'digits') ) {
        $base .= '0123456789';
      }

      $value = '';
      do {
        foreach (str_split(base64_encode(random_bytes($length))) as $random) {
          if ( strpos($base, $random) !== false ) {
            $value .= $random;
          }
        }
      } while ( strlen($value) < $length );

      if ( strlen($value) > $length ) {
        $value = substr($value, 0, $length);
      }

      return $value;
    }

  }
