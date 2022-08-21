<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  System::set_time_limit(0);

  $table_headers = [
    TABLE_HEADING_TABLE,
    TABLE_HEADING_MSG_TYPE,
    TABLE_HEADING_MSG,
    $masterblaster,
  ];

  $table_data = [];

  foreach ( $_POST['id'] as $table ) {
    $table = Text::input($table);
    $tickable = new Tickable('id[]', ['value' => $table], 'checkbox');
    if (isset($_POST['id']) && in_array($table, $_POST['id'])) {
      $tickable->tick();
    }

    $table = $db->escape($table);
    $tables_query = $db->query("$command TABLE $table");
    $table = htmlspecialchars($table);
    while ( $table_info = $tables_query->fetch_assoc() ) {
      $table_data[] = [
        $table,
        htmlspecialchars($table_info['Msg_type']),
        htmlspecialchars($table_info['Msg_text']),
        $tickable,
      ];

      $table = $tickable = '';
    }
  }
