<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $tax_class_id = Text::input($_GET['tID']);
  $sql_data = [
    'tax_class_title' => Text::prepare($_POST['tax_class_title']),
    'tax_class_description' => Text::prepare($_POST['tax_class_description']),
    'last_modified' => 'NOW()',
  ];

  $db->perform('tax_class', $sql_data, 'update', 'tax_class_id = ' . (int)$tax_class_id);

  return $link->set_parameter('tID', (int)$tax_class_id);
