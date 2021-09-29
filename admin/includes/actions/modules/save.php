<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  foreach ($_POST['configuration'] as $key => $value) {
    if (is_array($value)) {
      $value = implode(';', $value);
    }

    $key = Text::input($key);
    $value = Text::prepare($value);
    $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($value) . "' WHERE configuration_key = '" . $db->escape($key) . "'");
  }

  return $Admin->link('modules.php', ['set' => $set, 'module' => $_GET['module']]);
