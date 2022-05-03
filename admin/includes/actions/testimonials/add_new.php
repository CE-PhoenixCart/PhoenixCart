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
  $testimonial = Text::prepare($_POST['testimonials_text']);

  $db->query("INSERT INTO testimonials (customers_id, customers_name, date_added, testimonials_status) VALUES (" . $customers_id . ", '" . $db->escape($customers_name) . "', NOW(), 1)");
  $insert_id = mysqli_insert_id($db);
  $db->query("INSERT INTO testimonials_description (testimonials_id, languages_id, testimonials_text) VALUES (" . (int)$insert_id . ", " . (int)$_SESSION['languages_id'] . ", '" . $db->escape($testimonial) . "')");

  return $Admin->link()->retain_query_except(['action']);
