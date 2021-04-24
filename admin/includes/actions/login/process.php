<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_SESSION['redirect_origin']['auth_user']) && !isset($_POST['username'])) {
    $username = Text::input($_SESSION['redirect_origin']['auth_user']);
    $password = Text::input($_SESSION['redirect_origin']['auth_pw']);
  } else {
    $username = Text::input($_POST['username']);
    $password = Text::input($_POST['password']);
  }

  $actionRecorder = new actionRecorderAdmin('ar_admin_login', null, $username);

  if ($actionRecorder->canPerform()) {
    $check_query = $db->query("SELECT id, user_name, user_password FROM administrators WHERE user_name = '" . $db->escape($username) . "'");

    if (mysqli_num_rows($check_query) === 1) {
      $check = $check_query->fetch_assoc();

      if (Password::validate($password, $check['user_password'])) {
// migrate password if using an older hashing method
        if (Password::needs_rehash($check['user_password'])) {
          $db->query("UPDATE administrators SET user_password = '" . Password::hash($password) . "' WHERE id = " . (int)$check['id']);
        }

        $_SESSION['admin'] = [
          'id' => $check['id'],
          'username' => $check['user_name'],
        ];

        $actionRecorder->_user_id = $_SESSION['admin']['id'];
        $actionRecorder->record();

        $admin_hooks->cat('processActionSuccess');
        Form::reset_session_token();

        if (isset($_SESSION['redirect_origin'])) {
          $link = $Admin->link($_SESSION['redirect_origin']['page'], $_SESSION['redirect_origin']['get']);
          unset($_SESSION['redirect_origin']);

          Href::redirect($link);
        } else {
          Href::redirect($Admin->link('index.php'));
        }
      }

      $admin_hooks->cat('processActionFail');
    }

    if (isset($_POST['username'])) {
      $messageStack->add(ERROR_INVALID_ADMINISTRATOR, 'error');
    }
  } else {
    $messageStack->add(sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES') ? (int)MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES : 5)));
  }

  if (isset($_POST['username'])) {
    $actionRecorder->record(false);
  }
