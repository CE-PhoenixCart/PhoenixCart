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

  $expires_date = Text::input($_POST['expdate']);
  if (Text::is_empty($expires_date)) {
    $expires_date = 'NULL';
  } else {
    $expires_date = substr($expires_date, 0, 4) . substr($expires_date, 5, 2) . substr($expires_date, 8, 2);
  }

  $specials_id = Text::input($_POST['specials_id']);
  $db->perform('specials', [
    'specials_new_products_price' => $specials_price,
    'specials_last_modified' => 'NOW()',
    'expires_date' => $expires_date,
  ], 'update', 'specials_id = ' . (int)$specials_id);

  return $Admin->link('specials.php')->retain_query_except(['action'])->set_parameter('sID', (int)$specials_id);