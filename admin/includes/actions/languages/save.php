<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $sql_data = [
    'name' => Text::prepare($_POST['name']),
    'code' => Text::prepare(substr($_POST['code'], 0, 2)),
    'image' => Text::prepare($_POST['image']),
    'directory' => Text::prepare($_POST['directory']),
    'sort_order' => (int)Text::input($_POST['sort_order']),
  ];

  $lID = Text::input($_GET['lID']);
  $db->perform('languages', $sql_data, 'update', "languages_id = " . (int)$lID);

  if (isset($_POST['default']) && ('on' === $_POST['default'])) {
    $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($sql_data['code']) . "' WHERE configuration_key = 'DEFAULT_LANGUAGE'");
  }

  return $link->set_parameter('lID', (int)$lID);
