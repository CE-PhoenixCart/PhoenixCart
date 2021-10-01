<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  chdir('../../../../');
  require 'includes/application_top.php';

  $hooks->register_pipeline('loginRequired');

  if (!isset($_GET['products_id'])) {
    Href::redirect($Linker->build('index.php'));
  }

  if (!($product instanceof Product) || !$product->get('status')) {
    Href::redirect($Linker->build('product_info.php', ['products_id' => (int)$product->get('id')]));
  }

  require language::map_to_translation('modules/content/reviews/write.php');

  $reviewed_query = $db->query(sprintf(<<<'EOSQL'
SELECT products_id FROM reviews WHERE customers_id = %d AND products_id = %d LIMIT 1
EOSQL
    , (int)$_SESSION['customer_id'], (int)$product->get('id')));

  if (mysqli_num_results($reviewed_query) >= 1) {
    $messageStack->add_session('product_action', sprintf(TEXT_ALREADY_REVIEWED, $customer->get('short_name')), 'error');

    Href::redirect($Linker->build('product_info.php')->retain_query_except(['action']));
  }

  if (ALLOW_ALL_REVIEWS === 'false') {
    $reviewable_query = $db->query(sprintf(<<<'EOSQL'
SELECT op.products_id
 FROM orders_products op
  INNER JOIN orders o ON o.orders_id = op.orders_id
  LEFT JOIN reviews r ON o.customers_id = r.customers_id AND op.products_id = r.products_id
 WHERE o.customers_id = %d AND op.products_id = %d AND r.products_id IS NULL
 LIMIT 1
EOSQL
      , (int)$_SESSION['customer_id'], (int)$product->get('id')));

    if (!mysqli_num_results($reviewable_query)) {
      $messageStack->add_session('product_action', sprintf(TEXT_NOT_PURCHASED, $customer->get('short_name')), 'error');

      Href::redirect($Linker->build('product_info.php')->retain_query_except(['action']));
    }
  }

  if (Form::validate_action_is('process')) {
    $rating = Text::input($_POST['rating']);
    $review = Text::input($_POST['review']);
    $nickname = Text::input($_POST['nickname']);

    if ((ALLOW_ALL_REVIEWS === 'false') && ($_POST['nickname'] != $customer->get('short_name'))) {
      $nickname = sprintf(VERIFIED_BUYER, $nickname);
    }

    $db->query("INSERT INTO reviews (products_id, customers_id, customers_name, reviews_rating, date_added) VALUES ('" . (int)$_GET['products_id'] . "', '" . (int)$_SESSION['customer_id'] . "', '" . $db->escape($nickname) . "', '" . $db->escape($rating) . "', NOW())");
    $insert_id = mysqli_insert_id();

    $db->query("INSERT INTO reviews_description (reviews_id, languages_id, reviews_text) VALUES ('" . (int)$insert_id . "', '" . (int)$_SESSION['languages_id'] . "', '" . $db->escape($review) . "')");

    $hooks->cat('addNewAction');

    $messageStack->add_session('product_action', sprintf(TEXT_REVIEW_RECEIVED, $nickname), 'success');

    Href::redirect($Linker->build('product_info.php')->retain_query_except(['action']));
  }

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
