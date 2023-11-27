<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2023 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("UPDATE countries SET status = " . (int)$_GET['flag'] . " WHERE countries_id = " . (int)$_GET['cID']);

  return $Admin->link('countries.php')->retain_query_except(['action', 'flag']);
