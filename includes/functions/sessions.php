<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  function tep_session_start() {
    trigger_error('The tep_session_start function has been deprecated.', E_USER_DEPRECATED);
    return Session::start();
  }

  function tep_session_id($sessid = '') {
    trigger_error('The tep_session_id function has been deprecated.', E_USER_DEPRECATED);
    if (empty($sessid)) {
      return session_id();
    }

    return session_id($sessid);
  }

  function tep_session_destroy() {
    return Session::destroy();
  }

  function tep_session_recreate() {
    trigger_error('The tep_session_recreate function has been deprecated.', E_USER_DEPRECATED);
    Session::recreate();
  }

  function tep_reset_session_token() {
    trigger_error('The tep_reset_session_token function has been deprecated.', E_USER_DEPRECATED);
    Form::reset_session_token();
  }
