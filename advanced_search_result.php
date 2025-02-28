<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require language::map_to_translation('advanced_search.php');

  $error = false;

  if ( empty($_GET['keywords'])
    && !is_numeric($_GET['pfrom'] ?? null)
    && !is_numeric($_GET['pto'] ?? null)
    )
  {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  } else {
    $pfrom = $_GET['pfrom'] ?? '';
    $pto = $_GET['pto'] ?? '';
    $keywords = '';

    if (isset($_GET['keywords'])) {
      $keywords = Text::input($_GET['keywords']);
    }

    $price_check_error = false;
    if (!Text::is_empty($pfrom) && !settype($pfrom, 'double')) {
      $error = true;
      $price_check_error = true;

      $messageStack->add_session('search', ERROR_PRICE_FROM_MUST_BE_NUM);
    }

    if (!Text::is_empty($pto) && !settype($pto, 'double')) {
      $error = true;
      $price_check_error = true;

      $messageStack->add_session('search', ERROR_PRICE_TO_MUST_BE_NUM);
    }

    if (!$price_check_error && is_float($pfrom) && is_float($pto) && ($pfrom >= $pto)) {
      $error = true;

      $messageStack->add_session('search', ERROR_PRICE_TO_LESS_THAN_PRICE_FROM);
    }

    if (!Text::is_empty($keywords) && is_null($search_keywords = Search::build($keywords))) {
      $error = true;

      $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
    }
  }

  if (empty($pfrom) && empty($pto) && empty($keywords)) {
    $error = true;

    $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
  }

  if ($error) {
    Href::redirect($Linker->build('advanced_search.php')->retain_query_except());
  }

  $listing_sql = (new product_searcher([], []))->find();

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
