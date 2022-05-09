<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $pages_id = Text::input($_GET['pID']);

  $db->query("DELETE FROM pages WHERE pages_id = " . (int)$pages_id);
  $db->query("DELETE FROM pages_description WHERE pages_id = " . (int)$pages_id);

  return $link;
