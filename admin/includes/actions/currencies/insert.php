<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $code = Text::input($_POST['code']);

  $sql_data = [
    'title' => Text::prepare($_POST['title']),
    'code' => $code,
    'symbol_left' => Text::prepare($_POST['symbol_left']),
    'symbol_right' => Text::prepare($_POST['symbol_right']),
    'decimal_point' => Text::prepare($_POST['decimal_point']),
    'thousands_point' => Text::prepare($_POST['thousands_point']),
    'decimal_places' => Text::input($_POST['decimal_places']),
    'value' => Text::input($_POST['value']),
  ];

  $db->perform('currencies', $sql_data);
  $currency_id = mysqli_insert_id($db);

  if (isset($_POST['default']) && ('on' === $_POST['default'])) {
    $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($code) . "' WHERE configuration_key = 'DEFAULT_CURRENCY'");
  }

  return $Admin->link('currencies.php', ['page' => (int)($_GET['page'] ?? 1), 'cID' => (int)$currency_id]);
