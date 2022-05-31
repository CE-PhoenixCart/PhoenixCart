<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $newsletter_id = Text::input($_GET['nID']);

  $db->query("DELETE FROM newsletters WHERE newsletters_id = " . (int)$newsletter_id);

  return $link;
