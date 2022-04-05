<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $customers_id = (int)$_POST['customers_id'];
  $customers_name = Text::input($_POST['customer_name']);
  $testimonials_id = Text::input($_GET['tID']);
  $testimonials_text = Text::prepare($_POST['testimonials_text']);
  $testimonials_status = Text::input($_POST['testimonials_status']);

  $db->query("UPDATE testimonials SET customers_id = " . (int)$customers_id . ", customers_name  = '" . $db->escape($customers_name) . "', testimonials_status = '" . $db->escape($testimonials_status) . "', last_modified = NOW() WHERE testimonials_id = " . (int)$testimonials_id);
  $db->query("UPDATE testimonials_description SET testimonials_text = '" . $db->escape($testimonials_text) . "' WHERE testimonials_id = " . (int)$testimonials_id);

  return $link->set_parameter('tID', (int)$testimonials_id);
