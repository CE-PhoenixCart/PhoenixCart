<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $id = (int)$_GET['oID'];
  
  $sql_data = [
    'title' => Text::prepare($_POST['title']),
    'text' => Text::prepare($_POST['text']),
    'last_modified' => 'NOW()',
  ];

  $db->perform('outgoing_tpl', $sql_data, 'update', "id = " . (int)$id);

  return $link->set_parameter('oID', (int)$id);
