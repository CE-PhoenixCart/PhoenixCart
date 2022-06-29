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

  if (!($product instanceof Product)) {
    Href::redirect($Linker->build('product_info.php', ['products_id' => (int)$_GET['products_id']]));
  }

  require language::map_to_translation('modules/content/reviews/write.php');

  $hooks->register_pipeline('reviewable');

  if (Form::validate_action_is('process')) {
    $rating = Text::input($_POST['rating']);
    $review = Text::input($_POST['review']);
    $nickname = Text::input($_POST['nickname']);

    if ((ALLOW_ALL_REVIEWS === 'false') && ($_POST['nickname'] != $customer->get('short_name'))) {
      $nickname = sprintf(VERIFIED_BUYER, $nickname);
    }

    $db->query("INSERT INTO reviews (products_id, customers_id, customers_name, reviews_rating, date_added) VALUES (" . (int)$_GET['products_id'] . ", " . (int)$customer->get_id() . ", '" . $db->escape($nickname) . "', '" . $db->escape($rating) . "', NOW())");
    $insert_id = mysqli_insert_id($db);

    $db->query("INSERT INTO reviews_description (reviews_id, languages_id, reviews_text) VALUES (" . (int)$insert_id . ", " . (int)$_SESSION['languages_id'] . ", '" . $db->escape($review) . "')");

    $hooks->cat('addNewAction');

    $messageStack->add_session('product_action', sprintf(TEXT_REVIEW_RECEIVED, $nickname), 'success');

    Href::redirect($Linker->build('product_info.php')->retain_query_except(['action']));
  }

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
