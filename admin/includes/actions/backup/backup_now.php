<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  System::set_time_limit(0);
  $backup_file = 'db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';
  $fp = fopen(DIR_FS_BACKUP . $backup_file, 'w');

  fputs($fp, sprintf(<<<'EOSQL'
# CE Phoenix, E-Commerce made Easy
# https://phoenixcart.org
#
# Database Backup For %s
# Copyright (c) %d %s
#
# Database: %s
# Database Server: %s
#
# Backup Date: %s

EOSQL
    , STORE_NAME, date('Y'), STORE_OWNER, DB_DATABASE, DB_SERVER, date(PHP_DATE_TIME_FORMAT)));

  $tables_query = $db->query('SHOW TABLES');
  while ($tables = $tables_query->fetch_assoc()) {
    $table = reset($tables);

    $schema = "\n" . 'DROP TABLE IF EXISTS ' . $table . ';' . "\n"
            . 'CREATE TABLE ' . $table . ' (' . "\n";

    $table_list = [];
    $fields_query = $db->query("SHOW FIELDS FROM " . $table);
    while ($fields = $fields_query->fetch_assoc()) {
      $table_list[] = $fields['Field'];

      $schema .= '  ' . $fields['Field'] . ' ' . $fields['Type'];

      if (strlen($fields['Default'] ?? '') > 0) {
        $schema .= ' default \'' . $fields['Default'] . '\'';
      }

      if ($fields['Null'] != 'YES') {
        $schema .= ' NOT NULL';
      }

      if (!empty($fields['Extra'])) {
        $schema .= ' ' . strtoupper($fields['Extra']);
      }

      $schema .= ',' . "\n";
    }

    $schema = preg_replace("/,\n$/", '', $schema);

// add the keys
    $indexes = [];
    $keys_query = $db->query("SHOW KEYS FROM " . $table);
    while ($keys = $keys_query->fetch_assoc()) {
      $key_name = $keys['Key_name'];

      if (!isset($indexes[$key_name])) {
        $indexes[$key_name] = [
          'unique' => !$keys['Non_unique'],
          'fulltext' => ($keys['Index_type'] == 'FULLTEXT' ? '1' : '0'),
          'columns' => [],
        ];
      }

      $indexes[$key_name]['columns'][] = $keys['Column_name'];
    }

    foreach ($indexes as $key_name => $info) {
      $schema .= ',' . "\n";

      if ($key_name == 'PRIMARY') {
        $schema .= '  PRIMARY KEY (';
      } elseif ( $info['fulltext'] == '1' ) {
        $schema .= '  FULLTEXT ' . $key_name . ' (';
      } elseif ($info['unique']) {
        $schema .= '  UNIQUE ' . $key_name . ' (';
      } else {
        $schema .= '  KEY ' . $key_name . ' (';
      }
      $schema .= implode(', ', $info['columns']) . ')';
    }

    $schema .= "\n" . ');' . "\n\n";
    fputs($fp, $schema);

// dump the data
    if ( ($table !== 'sessions' ) && ($table !== 'whos_online') ) {
      $rows_query = $db->query("SELECT " . implode(',', $table_list) . " FROM " . $table);
      while ($row = $rows_query->fetch_assoc()) {
        $schema = 'INSERT INTO ' . $table . ' (' . implode(', ', $table_list) . ') VALUES (';

        foreach ($table_list as $k) {
          if (!isset($row[$k])) {
            $schema .= 'NULL, ';
          } elseif (Text::is_empty($row[$k])) {
            $schema .= "'', ";
          } else {
            $cell = $db->escape($row[$k]);
            $cell = preg_replace("/\n#/", "\n".'\#', $cell);

            $schema .= "'$cell', ";
          }
        }

        $schema = preg_replace('/, $/', '', $schema) . ');' . "\n";
        fputs($fp, $schema);
      }
    }
  }

  fclose($fp);

  switch ($_POST['compress']) {
    case 'gzip':
      exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
      $backup_file .= '.gz';
      break;
    case 'zip':
      exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . "$backup_file.zip " . DIR_FS_BACKUP . $backup_file);
      unlink(DIR_FS_BACKUP . $backup_file);
      $backup_file .= '.zip';
  }

  if (isset($_POST['download']) && ('yes' === $_POST['download'])) {
    header('Content-type: application/x-octet-stream');
    header('Content-disposition: attachment; filename=' . $backup_file);

    readfile(DIR_FS_BACKUP . $backup_file);
    unlink(DIR_FS_BACKUP . $backup_file);

    exit();
  } else {
    $messageStack->add_session(SUCCESS_DATABASE_SAVED, 'success');
  }

  return $Admin->link();
