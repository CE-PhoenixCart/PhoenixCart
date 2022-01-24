<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $currencies_id = Text::input($_GET['cID']);

  $currency = $db->query("SELECT currencies_id FROM currencies WHERE code = '" . $db->escape(DEFAULT_CURRENCY) . "'")->fetch_assoc();

  if ($currency['currencies_id'] == $currencies_id) {
    $db->query("update configuration set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
  }

  $db->query("DELETE FROM currencies WHERE currencies_id = " . (int)$currencies_id);

  return $Admin->link('currencies.php', ['page' => (int)($_GET['page'] ?? 1)]);
