<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if ( isset($_GET['pID'], $_GET['flag']) && ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
    $db->query("UPDATE pages SET pages_status = " . (int)$_GET['flag'] . " WHERE pages_id = " . (int)$_GET['pID']);
  }

  return $link->set_parameter('pID', (int)$_GET['pID']);
