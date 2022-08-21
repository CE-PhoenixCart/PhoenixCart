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

  $check_query = $db->query("SELECT id FROM administrators WHERE user_name = '" . $db->escape($username) . "' LIMIT 1");

  if (mysqli_num_rows($check_query) < 1) {
    $db->query("INSERT INTO administrators (user_name, user_password) VALUES ('" . $db->escape($username) . "', '" . $db->escape(Password::hash($password)) . "')");

    if (is_array($htpasswd_lines)) {
      foreach ($htpasswd_lines as $i => $htpasswd_line) {
        list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

        if ($ht_username == $username) {
          unset($htpasswd_lines[$i]);
        }
      }

      if (isset($_POST['htaccess']) && ($_POST['htaccess'] === 'true')) {
        $htpasswd_lines[] = $username . ':' . apache_password::hash($password);
      }

      file_put_contents($htpasswd_path, implode("\n", $htpasswd_lines));

      if (empty($htpasswd_lines)) {
        $htaccess_lines = array_diff($htaccess_lines, $authuserfile_lines);
      } elseif (!in_array('AuthUserFile ' . DIR_FS_ADMIN . '.htpasswd_phoenix', $htaccess_lines)) {
        array_splice($htaccess_lines, count($htaccess_lines), 0, $authuserfile_lines);
      }

      file_put_contents($htaccess_path, implode("\n", $htaccess_lines));
    }
  } else {
    $messageStack->add_session(ERROR_ADMINISTRATOR_EXISTS, 'error');
  }

  return $Admin->link('administrators.php');
