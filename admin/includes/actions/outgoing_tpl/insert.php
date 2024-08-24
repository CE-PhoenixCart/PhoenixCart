<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $sql_data = [
    'slug' => Text::input($_POST['slug']),
    'title' => Text::prepare($_POST['title']),
    'text' => Text::prepare($_POST['text']),
    'date_added' => 'NOW()',
  ];

  $db->perform('outgoing_tpl', $sql_data);
  $id = mysqli_insert_id($db);

  return $link->set_parameter('oID', (int)$id);
