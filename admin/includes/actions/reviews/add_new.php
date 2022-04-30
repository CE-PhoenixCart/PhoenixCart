<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $products_id = Text::input($_POST['products_id']);
  $customer_id = Text::input($_POST['customer_id']);
  $review = Text::prepare($_POST['reviews_text']);
  $rating = Text::input($_POST['reviews_rating']);
  $customer_name = (new customer((int)$customer_id))->get('name');

  $db->perform('reviews', [
    'products_id' => (int)$products_id,
    'customers_id' => (int)$customer_id,
    'customers_name' => $customer_name,
    'reviews_rating' => (int)$rating,
    'date_added' => 'NOW()',
    'reviews_status' => 1,
  ]);
  $db->perform('reviews_description', [
    'reviews_id' => (int)mysqli_insert_id($db),
    'languages_id' => (int)$_SESSION['languages_id'],
    'reviews_text' => $review,
  ]);

  return $link->retain_query_except(['action']);
