<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  Guarantor::ensure_global('Admin');
  $action = $_GET['action'] ?? '';
  $admin_hooks->cat('preAction');

  if ($action && ($action_file = $Admin->locate_action($action))) {
    $action_redirect = require $action_file;
    $admin_hooks->cat(Admin::camel_case($action) . 'Action');

    if (!is_null($action_redirect)) {
      Href::redirect($action_redirect);
    }
  }

  $admin_hooks->cat('postAction');
  if (!isset($_SESSION['sessiontoken'])) {
    Form::reset_session_token();
  }
