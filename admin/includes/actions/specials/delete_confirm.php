<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("DELETE FROM specials WHERE specials_id = " . (int)Text::input($_GET['sID']));

  return $Admin->link('specials.php')->retain_query_except(['action', 'sID']);
