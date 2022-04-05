<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $testimonials_id = Text::input($_GET['tID']);

  $db->query("DELETE FROM testimonials WHERE testimonials_id = " . (int)$testimonials_id);
  $db->query("DELETE FROM testimonials_description WHERE testimonials_id = " . (int)$testimonials_id);

  return $link;
