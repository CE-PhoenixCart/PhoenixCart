<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $lID = Text::input($_GET['lID']);

  $lng_query = $db->query("SELECT languages_id FROM languages WHERE code = '" . DEFAULT_LANGUAGE . "'");
  $lng = $lng_query->fetch_assoc();
  if ($lng['languages_id'] == $lID) {
    $remove_language = false;
    $messageStack->add(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
    $action = 'delete';
    return;
  }

  $db->query("DELETE FROM categories_description WHERE language_id = " . (int)$lID);
  $db->query("DELETE FROM products_description WHERE language_id = " . (int)$lID);
  $db->query("DELETE FROM products_options WHERE language_id = " . (int)$lID);
  $db->query("DELETE FROM products_options_values WHERE language_id = " . (int)$lID);
  $db->query("DELETE FROM manufacturers_info WHERE languages_id = " . (int)$lID);
  $db->query("DELETE FROM orders_status WHERE language_id = " . (int)$lID);
  $db->query("DELETE FROM customer_data_groups WHERE language_id = " . (int)$lID);
  $db->query("DELETE FROM languages WHERE languages_id = " . (int)$lID);

  return $link;
