<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("UPDATE specials SET status = " . (int)$_GET['flag'] . ", date_status_change = NOW() WHERE specials_id = " . (int)$_GET['sID']);

  return $Admin->link('specials.php')->retain_query_except(['action', 'flag']);
