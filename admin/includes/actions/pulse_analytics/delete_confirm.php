<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  $db->query("DELETE FROM analytics_events WHERE id = " . (int)$_GET['aID']);

  return $Admin->link('pulse_analytics.php')->retain_query_except(['action', 'aID']);
