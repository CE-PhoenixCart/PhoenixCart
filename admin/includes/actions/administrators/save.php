<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $username = Text::input($_POST['username']);
  $password = Text::input($_POST['password']);

  $check = $db->query("SELECT id, user_name FROM administrators WHERE id = " . (int)$_GET['aID'])->fetch_assoc();

// update username in current session if changed
  if ( ($check['id'] == $_SESSION['admin']['id']) && ($check['user_name'] != $_SESSION['admin']['username']) ) {
    $_SESSION['admin']['username'] = $username;
  }

// update username in htpasswd if changed
  if (is_array($htpasswd_lines) && ($check['user_name'] !== $username)) {
    foreach ($htpasswd_lines as $i => $htpasswd_line) {
      if (false === strpos($htpasswd_line, ':')) {
        continue;
      }

      list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

      if ($check['user_name'] === $ht_username) {
        $htpasswd_lines[$i] = "$username:$ht_password";
      }
    }
  }

  $db->query("UPDATE administrators SET user_name = '" . $db->escape($username) . "' WHERE id = " . (int)$_GET['aID']);

  if (!Text::is_empty($password)) {
// update password in htpasswd
    if (is_array($htpasswd_lines)) {
      foreach ($htpasswd_lines as $i => $htpasswd_line) {
        list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

        if ($ht_username == $username) {
          unset($htpasswd_lines[$i]);
        }
      }

      if (isset($_POST['htaccess']) && ($_POST['htaccess'] === 'true')) {
        $htpasswd_lines[] = "$username:" . apache_password::hash($password);
      }
    }

    $db->query("UPDATE administrators SET user_password = '" . $db->escape(Password::hash($password)) . "' WHERE id = " . (int)$_GET['aID']);
  } elseif (!isset($_POST['htaccess']) || ($_POST['htaccess'] !== 'true')) {
    if (is_array($htpasswd_lines)) {
      foreach ($htpasswd_lines as $i => $htpasswd_line) {
        list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

        if ($ht_username == $username) {
          unset($htpasswd_lines[$i]);
        }
      }
    }
  }

// write new htpasswd file
  if (is_array($htpasswd_lines)) {
    file_put_contents($htpasswd_path, implode("\n", $htpasswd_lines));

    if (empty($htpasswd_lines)) {
      $htaccess_lines = array_diff($htaccess_lines, $authuserfile_lines);
    } elseif (!in_array('AuthUserFile ' . DIR_FS_ADMIN . '.htpasswd_phoenix', $htaccess_lines)) {
      array_splice($htaccess_lines, count($htaccess_lines), 0, $authuserfile_lines);
    }

    file_put_contents($htaccess_path, implode("\n", $htaccess_lines));
  }

  return $Admin->link('administrators.php', ['aID' => (int)$_GET['aID']]);
