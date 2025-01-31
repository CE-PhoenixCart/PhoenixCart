<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $customers_id = Text::input($_GET['cID']);

  if (isset($_POST['delete_reviews']) && ($_POST['delete_reviews'] === 'on')) {
    $db->query("DELETE r, rd FROM reviews r LEFT JOIN reviews_description rd ON r.reviews_id = rd.reviews_id WHERE r.customers_id = " . (int)$customers_id);
  } else {
    $db->query("UPDATE reviews SET customers_id = NULL, customers_name = '" . $db->escape(CUSTOMER_REVIEW_ANONYMIZED) . "' WHERE customers_id = " . (int)$customers_id);
  }

  $db->query("DELETE FROM address_book WHERE customers_id = " . (int)$customers_id);
  $db->query("DELETE FROM customers WHERE customers_id = " . (int)$customers_id);
  $db->query("DELETE FROM customers_info WHERE customers_info_id = " . (int)$customers_id);
  $db->query("DELETE FROM customers_basket WHERE customers_id = " . (int)$customers_id);
  $db->query("DELETE FROM customers_basket_attributes WHERE customers_id = " . (int)$customers_id);
  $db->query("DELETE FROM whos_online WHERE customer_id = " . (int)$customers_id);
  $db->query("DELETE FROM products_notifications WHERE customers_id = " . (int)$customers_id);
  $db->query("DELETE t, td FROM testimonials t LEFT JOIN testimonials_description td ON t.testimonials_id = td.testimonials_id WHERE t.customers_id = " . (int)$customers_id);
  $db->query("DELETE FROM outgoing WHERE customer_id = " . (int)$customers_id);

  return $Admin->link('customers.php')->retain_query_except(['cID', 'action']);
