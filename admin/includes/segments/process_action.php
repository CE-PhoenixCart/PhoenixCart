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

  if ($action_file = $Admin->locate_action($action)) {
    $hook_action = Admin::camel_case($action ?: 'default') . 'Action';
    $action_redirect = require $action_file;
    $admin_hooks->cat($hook_action);

    if (is_string($action_redirect) || ($action_redirect instanceof Href)) {
      Href::redirect($action_redirect);
    }
  }

  $admin_hooks->cat('postAction');
  if (!isset($_SESSION['sessiontoken'])) {
    Form::reset_session_token();
  }
