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
    'last_modified' => 'NOW()',
  ];

  $db->perform('outgoing_tpl', $sql_data, 'update', "id = " . $id);

  foreach (array_column(language::load_all(), 'id') as $language_id) {
    $sql_data = [
      'title' => Text::prepare($_POST['title'][$language_id]),
      'text' => Text::prepare($_POST['text'][$language_id]),
      'languages_id' => $language_id,
    ];

    $db->perform('outgoing_tpl_info', $sql_data, 'update', "id = " . $id . " AND languages_id = " . (int)$language_id);
  }

  return $link->set_parameter('oID', $id);
