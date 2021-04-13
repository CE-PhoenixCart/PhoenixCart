<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class apache_password {

    /**
     * If Apache 2.4.4 or later, we have bcrypt.
     * @return boolean
     */
    public static function has_bcrypt() {
      if (defined('APACHE_ENCRYPTION')) {
        return 'APR-MD5' !== APACHE_ENCRYPTION;
      }

      if (isset($_SERVER['SERVER_SOFTWARE']) && (false !== ($i = stripos($_SERVER['SERVER_SOFTWARE'], 'apache')))) {
        $apache_version = explode(' ', $_SERVER['SERVER_SOFTWARE']);
        return isset($apache_version[0]) && version_compare(substr($apache_version[0], $i + strlen('Apache/')), '2.4.4', '>=');
      }

      return false;
    }

    /**
     * https://stackoverflow.com/q/1038791 (originally from php.net comment)
     * Copyright on the original C code would be owned by Apache.
     * @param string $plain
     * @return string The password hash.
     */
    public static function hash($plain) {
      if (static::has_bcrypt()) {
// use bcrypt (or a future replacement) if we can
        $encryption = defined('APACHE_ENCRYPTION')
                    ? APACHE_ENCRYPTION
                    : PASSWORD_BCRYPT;

        return defined('APACHE_PASSWORD_OPTIONS')
             ? password_hash($plain, $encryption, APACHE_PASSWORD_OPTIONS)
             : password_hash($plain, $encryption);
      }
// otherwise fall back to APR-MD5

// we want a salt of length 8, so we generate 6 random bytes
// and then the base64_encode converts that to 8 characters
      $salt = strtr(base64_encode(random_bytes(6)), '+', '.');
      $bin = pack('H32', md5("$plain$salt$plain"));

      $text = $plain . '$apr1$' . $salt;
      for ($i = strlen($plain); $i > 0; $i -= 16) {
        $text .= substr($bin, 0, min(16, $i));
      }

      for ($i = strlen($plain); $i > 0; $i >>= 1) {
        $text .= ($i & 1) ? chr(0) : $plain[0];
      }

      $bin = pack('H32', md5($text));

      for ($i = 0; $i < 1000; $i++) {
        $odd = $i & 1;
        $new = $odd ? $plain : $bin;

        if ($i % 3) {
          $new .= $salt;
        }

        if ($i % 7) {
          $new .= $plain;
        }

        $new .= $odd ? $bin : $plain;

        $bin = pack('H32', md5($new));
      }

      $result = chr(0) . chr(0) . $bin[11]
              . $bin[4] . $bin[10] . $bin[5]
              . $bin[3] . $bin[9] . $bin[15]
              . $bin[2] . $bin[8] . $bin[14]
              . $bin[1] . $bin[7] . $bin[13]
              . $bin[0] . $bin[6] . $bin[12];
      $result = strtr(
        strrev(substr(base64_encode($result), 2)),
        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/',
        './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');

      return '$apr1$' . $salt . '$' . $result;
    }

  }
