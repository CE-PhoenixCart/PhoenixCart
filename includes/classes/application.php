<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Application {

    public function check_ssl_session_id() {
// verify the ssl_session_id if the feature is enabled
      if (SESSION_CHECK_SSL_SESSION_ID === 'True') {
        Request::check_ssl_session_id();
      }
    }

    public function check_user_agent() {
// verify the browser user agent if the feature is enabled
      if (SESSION_CHECK_USER_AGENT === 'True') {
        Request::check_user_agent();
      }
    }

    public function check_ip() {
// verify the IP address if the feature is enabled
      if (SESSION_CHECK_IP_ADDRESS === 'True') {
        Request::check_ip();
      }
    }

    public function ensure_session_cart() {
      if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof shoppingCart)) {
        $_SESSION['cart'] = new shoppingCart();
      }
    }

    public function fix_numeric_locale() {
      static $_system_locale_numeric = 0;

// Prevent LC_ALL from setting LC_NUMERIC to a locale with 1,0 float/decimal values instead of 1.0 (see bug #634)
      $_system_locale_numeric = setlocale(LC_NUMERIC, $_system_locale_numeric);
    }

    public function set_session_language() {
      if (empty($_SESSION['language']) || isset($_GET['language'])) {
        $GLOBALS['lng'] = language::build();

        $GLOBALS['languages_id'] =& $_SESSION['languages_id'];
        $GLOBALS['language'] =& $_SESSION['language'];
      }

      class_exists('Text');
      $GLOBALS['oscTemplate'] =& Guarantor::ensure_global('Template');
      $GLOBALS['class_index']->set_translator('language::map_to_translation');
      $this->fix_numeric_locale();
      return language::map_to_translation('.php');
    }

    public function set_template_title() {
      Guarantor::ensure_global('Template')->set_title(TITLE);
    }

    public function ensure_navigation_history() {
      if (!isset($_SESSION['navigation']) || !($_SESSION['navigation'] instanceof navigationHistory)) {
        $_SESSION['navigation'] = new navigationHistory();
        $GLOBALS['navigation'] = &$_SESSION['navigation'];
      }

      $_SESSION['navigation']->add_current_page();
    }

    public function set_customer_if_identified() {
      if (isset($_SESSION['customer_id'])) {
        $GLOBALS['customer'] = new customer($_SESSION['customer_id']);
      }
    }

  }
