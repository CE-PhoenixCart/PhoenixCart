<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $sql_data = [
    'tax_zone_id' => (int)Text::input($_POST['tax_zone_id']),
    'tax_class_id' => (int)Text::input($_POST['tax_class_id']),
    'tax_rate' => Text::input($_POST['tax_rate']),
    'tax_description' => Text::prepare($_POST['tax_description']),
    'tax_priority' => (int)Text::input($_POST['tax_priority']),
    'date_added' => 'NOW()',
  ];

  $db->perform('tax_rates', $sql_data);

  return $Admin->link();
