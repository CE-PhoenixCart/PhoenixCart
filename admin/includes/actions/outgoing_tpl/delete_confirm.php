<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $id = (int)$_GET['oID'];
  $slug = $_GET['slug'] ?? '';

  $db->query("DELETE FROM outgoing_tpl WHERE id = " . $id);
  $db->query("DELETE FROM outgoing_tpl_info WHERE id = " . $id);

  $db->query("DELETE FROM outgoing WHERE slug = " . $db->normalize_value($slug));

  return $link;
