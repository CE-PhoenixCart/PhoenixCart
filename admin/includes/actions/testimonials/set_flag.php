<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if ( isset($_GET['tID'], $_GET['flag']) && ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
    $db->query("UPDATE testimonials SET testimonials_status = " . (int)$_GET['flag'] . " WHERE testimonials_id = " . (int)$_GET['tID']);
  }

  return $link->set_parameter('tID', (int)$_GET['tID']);
