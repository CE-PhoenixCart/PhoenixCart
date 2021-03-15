<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Request {

    const IP_KEYS = [
      'HTTP_CLIENT_IP',
      'HTTP_X_CLUSTER_CLIENT_IP',
      'HTTP_PROXY_USER',
      'REMOTE_ADDR',
    ];

    protected static $page;

    protected static function yield_ip() {
      if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        foreach ( array_reverse(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])) as $x_ip ) {
          yield trim($x_ip);
        }
      }

      foreach (static::IP_KEYS as $key) {
        if (isset($_SERVER['$key'])) {
          yield $_SERVER['$key'];
        }
      }
    }

    public static function get_ip() {
      foreach ( static::yield_ip() as $ip ) {
        if (filter_var($ip, FILTER_VALIDATE_IP, ['flags' => FILTER_FLAG_IPV4])) {
          return $ip;
        }
      }

      return false;
    }

    public static function get_page() {
      if (is_null(static::$page)) {
        static::$page = Text::ltrim_once(
          parse_url($_SERVER['SCRIPT_NAME'])['path'], DIR_WS_CATALOG);
      }

      return static::$page;
    }

    public static function is_ssl() {
      return (getenv('HTTPS') === 'on');
    }

    public static function value($name) {
      return $_GET[$name] ?? $_POST[$name] ?? null;
    }

    public static function check_ssl_session_id() {
// verify the ssl_session_id is the same as previously recorded
      if ( static::is_ssl() && $GLOBALS['session_started'] ) {
        $ssl_session_id = getenv('SSL_SESSION_ID');
        if (!isset($_SESSION['SSL_SESSION_ID'])) {
          $_SESSION['SSL_SESSION_ID'] = $ssl_session_id;
        }

        if ($_SESSION['SSL_SESSION_ID'] !== $ssl_session_id) {
          tep_session_destroy();
          tep_redirect(tep_href_link('ssl_check.php'));
        }
      }
    }

    public static function check_user_agent() {
// verify the browser user agent is the same as previously recorded
      $http_user_agent = getenv('HTTP_USER_AGENT');
      if (!isset($_SESSION['SESSION_USER_AGENT'])) {
        $_SESSION['SESSION_USER_AGENT'] = $http_user_agent;
      }

      if ($_SESSION['SESSION_USER_AGENT'] !== $http_user_agent) {
        tep_session_destroy();
        tep_redirect(tep_href_link('login.php'));
      }
    }

    public static function check_ip() {
// verify the IP address is the same as previously recorded
      $ip_address = static::get_ip();
      if (!isset($_SESSION['SESSION_IP_ADDRESS'])) {
        $_SESSION['SESSION_IP_ADDRESS'] = $ip_address;
      }

      if ($_SESSION['SESSION_IP_ADDRESS'] !== $ip_address) {
        tep_session_destroy();
        tep_redirect(tep_href_link('login.php'));
      }
    }

  }
