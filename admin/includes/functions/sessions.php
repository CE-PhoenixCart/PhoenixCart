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

  function tep_session_destroy() {
    trigger_error('The tep_session_destroy function has been deprecated.', E_USER_DEPRECATED);
    return Session::destroy();
  }
