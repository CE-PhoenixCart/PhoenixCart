<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (!in_array(basename($_GET['module']), array_column($module_files['installed'], 'code'))) {
    error_log("'{$_GET['module']}' not an installed module; can't save configuration.");
    Href::redirect($link);
  }

  foreach ($_POST['configuration'] as $key => $value) {
    $value = is_array($value) ? implode(';', array_map('Text::prepare', $value)) : Text::prepare($value);

    $key = Text::input($key);
    $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($value) . "' WHERE configuration_key = '" . $db->escape($key) . "'");
  }

  return $link->set_parameter('module', $_GET['module']);
