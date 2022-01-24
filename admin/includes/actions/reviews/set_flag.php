<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("UPDATE reviews SET reviews_status = " . (int)$_GET['flag'] . ", last_modified = NOW() WHERE reviews_id = " . (int)$_GET['rID']);

  return $link->set_parameter('rID', (int)$_GET['rID']);
