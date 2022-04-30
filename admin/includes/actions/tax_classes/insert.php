<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $sql_data = [
    'tax_class_title' => Text::prepare($_POST['tax_class_title']),
    'tax_class_description' => Text::prepare($_POST['tax_class_description']),
    'date_added' => 'NOW()',
  ];

  $db->perform('tax_class', $sql_data);

  return $Admin->link();
