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
    'date_added' => 'NOW()',
  ];

  $db->perform('outgoing_tpl', $sql_data);
  
  $insert_id = mysqli_insert_id($db);

  foreach (array_column(language::load_all(), 'id') as $language_id) {
    $sql_data = [
      'id' => (int)$insert_id,
      'title' => Text::prepare($_POST['title'][$language_id]),
      'text' => Text::prepare($_POST['text'][$language_id]),
      'languages_id' => $language_id,
    ];

    $db->perform('outgoing_tpl_info', $sql_data);
  }

  return $link->set_parameter('oID', (int)$insert_id);
