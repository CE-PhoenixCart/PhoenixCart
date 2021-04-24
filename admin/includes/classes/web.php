<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Web {

    public static function load(string $url) {
      if (empty($url)) {
        error_log('Cannot load an empty URL');
        return;
      }

      if (ini_get('allow_url_fopen')) {
        return file_get_contents($url);
      }

      if (function_exists('curl_init')) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);

        return curl_exec($ch);
      }

      $web = new web_loader($url);
      return $web->load();
    }

    public static function load_xml(string $url) {
      if (empty($url)) {
        error_log('Cannot load an empty URL as XML');
        return;
      }

      if (ini_get('allow_url_fopen')) {
        return simplexml_load_file($url);
      }

      return simplexml_load_string(static::load($url));
    }

  }
