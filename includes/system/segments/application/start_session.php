<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  session_name('ceid');
  Session::set_save_location();

// set the session cookie parameters
  Cookie::save_session_parameters();

  if ( SESSION_FORCE_COOKIE_USE == 'False' ) {
    @ini_set('session.use_only_cookies', 0);

// set the session ID if it exists in the request parameters
    Session::request_id();
  }

// start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    @ini_set('session.use_only_cookies', 1);

    Cookie::save('cookie_test', 'please_accept_for_session');

    if (isset($_COOKIE['cookie_test'])) {
      $session_started = Session::start();
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (!Text::is_empty($user_agent)) {
      foreach (file('includes/spiders.txt') as $spider) {
        if (!Text::is_empty($spider) && is_integer(strpos($user_agent, trim($spider)))) {
          $spider_flag = true;
          break;
        }
      }
    }

    if (!$spider_flag) {
      $session_started = Session::start();
    }
  } else {
    $session_started = Session::start();
  }

  if (Session::is_started()) {
// register session variables globally
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }

// initialize a session token
  if (!isset($_SESSION['sessiontoken'])) {
    Form::reset_session_token();
  }

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');
