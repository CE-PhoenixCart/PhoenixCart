<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $configuration_value = Text::prepare($_POST['configuration_value'] ?? '');
  $cID = Text::input($_GET['cID']);

  $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($configuration_value) . "', last_modified = NOW() WHERE configuration_id = " . (int)$cID);

  return $link->set_parameter('cID', (int)$cID);
