<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $id = Text::input($_GET['oID']);
  
  $sql_data = [
    'send_at' => Text::input($_POST['send_at']),
    'slug' => Text::input($_POST['slug']),
    'email_address' => Text::input($_POST['email_address']),
    'merge_tags' => Text::prepare($_POST['text']),
    'last_modified' => 'NOW()',
  ];

  $db->perform('outgoing', $sql_data, 'update', "id = " . (int)$id);

  return $link->set_parameter('oID', (int)$id);
