<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if ( isset($_GET['aID'], $_GET['flag']) && ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
    $db->query("UPDATE advert SET status = " . (int)$_GET['flag'] . ", date_status_change = NOW() WHERE advert_id = " . (int)$_GET['aID']);
  }

  return $link->set_parameter('aID', (int)$_GET['aID']);
