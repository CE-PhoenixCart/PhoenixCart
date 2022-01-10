<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $currencies_id = Text::input($_GET['cID']);

  $currency = $db->query("SELECT code FROM currencies WHERE currencies_id = " . (int)$currencies_id)->fetch_assoc();

  $remove_currency = $currency['code'] == DEFAULT_CURRENCY;
  if ($remove_currency) {
    $messageStack->add(ERROR_REMOVE_DEFAULT_CURRENCY, 'error');
  }
