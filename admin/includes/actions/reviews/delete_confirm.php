<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $reviews_id = Text::input($_GET['rID']);

  $db->query("DELETE r, rd FROM reviews r INNER JOIN reviews_description rd ON r.reviews_id = rd.reviews_id WHERE r.reviews_id = " . (int)$reviews_id);

  return $link;
