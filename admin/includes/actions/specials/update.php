<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $specials_price = Text::input($_POST['specials_price']);
  if (substr($specials_price, -1) === '%') {
    $products_price = Text::input($_POST['products_price']);
    $specials_price = substr($specials_price, 0, -1);
    $specials_price = ($products_price - (($specials_price / 100) * $products_price));
  }

  $expdate = Text::input($_POST['expdate']);
  if (Text::is_empty($expdate)) {
    $expires_date = 'NULL';
  } else {
    $expires_date = date($expdate . ' H:i:s', strtotime('tomorrow -1 second'));
  }

  $specials_id = Text::input($_POST['specials_id']);
  $db->perform('specials', [
    'specials_new_products_price' => $specials_price,
    'specials_last_modified' => 'NOW()',
    'expires_date' => $expires_date,
  ], 'update', 'specials_id = ' . (int)$specials_id);

  return $Admin->link('specials.php')->retain_query_except(['action'])->set_parameter('sID', (int)$specials_id);