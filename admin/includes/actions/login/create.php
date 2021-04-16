<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (mysqli_num_rows($db->query("SELECT id FROM administrators LIMIT 1")) === 0) {
    $username = Text::input($_POST['username']);
    $password = Text::input($_POST['password']);

    if ( $username ) {
      $db->query("INSERT INTO administrators (user_name, user_password) VALUES ('" . $db->escape($username) . "', '" . $db->escape(Password::hash($password)) . "')");
    }
  }

  return $Admin->link('login.php');
