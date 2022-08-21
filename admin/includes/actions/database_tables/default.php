<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $table_headers = [
    TABLE_HEADING_TABLE,
    TABLE_HEADING_ROWS,
    TABLE_HEADING_SIZE,
    TABLE_HEADING_ENGINE,
    TABLE_HEADING_COLLATION,
    $masterblaster,
  ];

  $table_data = [];

  $tables_query = $db->query('SHOW TABLE STATUS');
  while ( $table_info = $tables_query->fetch_assoc() ) {
    $table_data[] = [
      htmlspecialchars($table_info['Name']),
      htmlspecialchars($table_info['Rows']),
      round(($table_info['Data_length'] + $table_info['Index_length']) / 1024 / 1024, 2) . 'M',
      htmlspecialchars($table_info['Engine']),
      htmlspecialchars($table_info['Collation']),
      new Tickable('id[]', ['value' => $table_info['Name']], 'checkbox'),
    ];
  }
