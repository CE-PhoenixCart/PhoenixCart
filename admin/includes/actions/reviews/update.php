<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $reviews_id = Text::input($_GET['rID']);

  $db->perform('reviews', [
    'reviews_rating' => (int)Text::input($_POST['reviews_rating']),
    'last_modified' => 'NOW()',
    'reviews_status' => (int)Text::input($_POST['reviews_status']),
  ], 'update', "reviews_id = " . (int)$reviews_id);
  $db->perform('reviews_description', [
    'reviews_text' => Text::prepare($_POST['reviews_text']),
  ], 'update', "reviews_id = " . (int)$reviews_id . " AND languages_id = " . (int)$_SESSION['languages_id']);

  return $link->set_parameter('rID', (int)$_GET['rID']);
