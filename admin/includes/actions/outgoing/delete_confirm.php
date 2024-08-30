<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $id = Text::input($_GET['oID']);

  $db->query("DELETE FROM outgoing WHERE id = " . (int)$id);

  return $link;
