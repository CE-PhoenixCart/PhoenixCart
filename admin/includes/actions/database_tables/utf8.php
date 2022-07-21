<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $charset_pass = isset($_POST['from_charset'])
               && (( 'auto' === $_POST['from_charset'] )
                  || in_array($_POST['from_charset'], array_column($mysql_charsets, 'id')));

  if ( $charset_pass === false ) {
    Href::redirect(Guarantor::ensure_global('Admin')->link());
  }

  System::set_time_limit(0);

  $table_headers = isset($_POST['dryrun'])
                 ? [TABLE_HEADING_QUERIES]
                 : [TABLE_HEADING_TABLE, TABLE_HEADING_MSG, $masterblaster];

  $table_data = [];

  foreach ( $_POST['id'] as $table ) {
    $result = 'OK';

    $queries = [];

    $cols_query = $db->query("SHOW FULL COLUMNS FROM " . $db->escape(Text::input($table)));
    while ( $cols = $cols_query->fetch_assoc() ) {
      if ( !empty($cols['Collation']) ) {
        $old_charset = ( 'auto' === $_POST['from_charset'] )
                     ? Text::input(substr($cols['Collation'], 0, strpos($cols['Collation'], '_')))
                     : Text::input($_POST['from_charset']);

        $queries[] = sprintf(<<<'EOSQL'
UPDATE %1$s
 SET %2$s = CONVERT(BINARY CONVERT(%2$s USING %3$s) USING utf8mb4)
 WHERE CHAR_LENGTH(%2$s) = LENGTH(CONVERT(BINARY CONVERT(%2$s USING %3$s) USING utf8mb4))
EOSQL
          , $db->escape(Text::input($table)), $cols['Field'], $db->escape($old_charset));
      }
    }

    $sql = sprintf("ALTER TABLE %s CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
      $db->escape(Text::input($table)));

    if ( isset($_POST['dryrun']) ) {
      $table_data[] = [$sql];

      foreach ( $queries as $q ) {
        $table_data[] = [$q];
      }
    } else {
// mysqli_query() is directly called as $db->query() dies when an error occurs
      if ( mysqli_query($db, $sql) ) {
        foreach ( $queries as $q ) {
          if ( !mysqli_query($db, $q) ) {
            $result = $db->error;
            break;
          }
        }
      } else {
        $result = $db->error;
      }

      $table_data[] = [
        htmlspecialchars($table),
        htmlspecialchars($result),
        (new Tickable('id[]', ['value' => $table], 'checkbox'))->tick(),
      ];
    }
  }
