<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $id = Text::input($_GET['aID']);

  $check = $db->query("SELECT id, user_name FROM administrators WHERE id = " . (int)$id)->fetch_assoc();

  if ($admin['id'] == $check['id']) {
    unset($_SESSION['admin']);
  }

  $db->query("DELETE FROM administrators WHERE id = " . (int)$id);

  if (is_array($htpasswd_lines)) {
    foreach ($htpasswd_lines as $i => $htpasswd_line) {
      list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

      if ($ht_username == $check['user_name']) {
        unset($htpasswd_lines[$i]);
      }
    }

    file_put_contents($htpasswd_path, implode("\n", $htpasswd_lines));

    if (empty($htpasswd_lines)) {
      file_put_contents($htaccess_path, implode("\n",
        array_diff($htaccess_lines, $authuserfile_lines)) . "\n");
    }
  }

  return $Admin->link('administrators.php');
