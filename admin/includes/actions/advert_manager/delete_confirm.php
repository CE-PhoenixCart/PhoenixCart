<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $advert_id = Text::input($_GET['aID']);

  $db->query("DELETE FROM advert WHERE advert_id = " . (int)$advert_id);
  $db->query("DELETE FROM advert_info WHERE advert_id = " . (int)$advert_id);

  return $link;
