<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $mysql_charsets = [['id' => 'auto', 'text' => ACTION_UTF8_CONVERSION_FROM_AUTODETECT]];

  $charsets_query = $db->query("SHOW CHARACTER SET");
  while ( $charsets = $charsets_query->fetch_assoc() ) {
    $mysql_charsets[] = ['id' => $charsets['Charset'], 'text' => sprintf(ACTION_UTF8_CONVERSION_FROM, $charsets['Charset'])];
  }

  $action = null;
  $actions = [
    [
      'id' => 'check',
      'text' => ACTION_CHECK_TABLES,
    ],
    [
      'id' => 'analyze',
      'text' => ACTION_ANALYZE_TABLES,
    ],
    [
      'id' => 'optimize',
      'text' => ACTION_OPTIMIZE_TABLES,
    ],
    [
      'id' => 'repair',
      'text' => ACTION_REPAIR_TABLES,
    ],
    [
      'id' => 'utf8',
      'text' => ACTION_UTF8_CONVERSION,
    ],
  ];

  if ( isset($_POST['action']) && !empty($_POST['id']) && is_array($_POST['id'])
    && in_array($_POST['action'], ['check', 'analyze', 'optimize', 'repair', 'utf8']) )
  {
    $tables = [];

    $tables_query = $db->query('SHOW TABLE STATUS');
    while ( $table = $tables_query->fetch_assoc() ) {
      $tables[] = $table['Name'];
    }

    foreach ( $_POST['id'] as $key => $value ) {
      if ( !in_array($value, $tables) ) {
        unset($_POST['id'][$key]);
      }
    }

    if ( !empty($_POST['id']) ) {
      $action = $_POST['action'];
    }
  }

  switch ( $action ) {
    case 'check':
    case 'analyze':
    case 'optimize':
    case 'repair':
      tep_set_time_limit(0);

      $table_headers = [
        TABLE_HEADING_TABLE,
        TABLE_HEADING_MSG_TYPE,
        TABLE_HEADING_MSG,
        new Tickable('masterblaster', ['type' => 'checkbox']),
      ];

      $table_data = [];

      foreach ( $_POST['id'] as $table ) {
        $table = Text::input($table);
        $tickable = new Tickable('id[]', ['type' => 'checkbox'])-set('value', $table);
        if (isset($_POST['id']) && in_array($table, $_POST['id'])) {
          $tickable->tick();
        }

        $table = $db->escape($table);
        $sql_query = $db->query("$action TABLE $table");
        $table = htmlspecialchars($table);
        while ( $sql = $sql_query->fetch_assoc() ) {
          $table_data[] = [
            $table,
            htmlspecialchars($sql['Msg_type']),
            htmlspecialchars($sql['Msg_text']),
            $tickable,
          ];

          $table = $tickable = '';
        }
      }

      break;

    case 'utf8':
      $charset_pass = isset($_POST['from_charset'])
                   && (( 'auto' === $_POST['from_charset'] )
                     || in_array($_POST['from_charset'], array_column($mysql_charsets, 'id')));

      if ( $charset_pass === false ) {
        Href::redirect(Guarantor::ensure_global('Admin')->link('database_tables.php'));
      }

      tep_set_time_limit(0);

      if ( isset($_POST['dryrun']) ) {
        $table_headers = [TABLE_HEADING_QUERIES];
      } else {
        $table_headers = [TABLE_HEADING_TABLE, TABLE_HEADING_MSG, new Tickable('masterblaster', ['type' => 'checkbox'])];
      }

      $table_data = [];

      foreach ( $_POST['id'] as $table ) {
        $result = 'OK';

        $queries = [];

        $cols_query = $db->query("SHOW FULL COLUMNS FROM " . $db->escape(Text::input($table)));
        while ( $cols = $cols_query->fetch_assoc() ) {
          if ( !empty($cols['Collation']) ) {
            if ( 'auto' === $_POST['from_charset'] ) {
              $old_charset = Text::input(substr($cols['Collation'], 0, strpos($cols['Collation'], '_')));
            } else {
              $old_charset = Text::input($_POST['from_charset']);
            }

            $queries[] = sprintf(<<<'EOSQL'
UPDATE %1$s
 SET %2$s = CONVERT(BINARY CONVERT(%2$s USING %3$s) USING utf8mb4)
 WHERE CHAR_LENGTH(%2$s) = LENGTH(CONVERT(BINARY CONVERT(%2$s USING %3$s) USING utf8mb4))
EOSQL
              , $db->escape(Text::input($table)), $cols['Field'], $old_charset);
          }
        }

        $query = sprintf("ALTER TABLE %s CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
          $db->escape(Text::input($table)));

        if ( isset($_POST['dryrun']) ) {
          $table_data[] = [$query];

          foreach ( $queries as $q ) {
            $table_data[] = [$q];
          }
        } else {
// mysqli_query() is directly called as $db->query() dies when an error occurs
          if ( mysqli_query($db, $query) ) {
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
            (new Tickable('id[]', ['type' => 'checkbox']))->set('value', $table)->tick(),
          ];
        }
      }

      break;

    default:
      $table_headers = [
        TABLE_HEADING_TABLE,
        TABLE_HEADING_ROWS,
        TABLE_HEADING_SIZE,
        TABLE_HEADING_ENGINE,
        TABLE_HEADING_COLLATION,
        new Tickable('masterblaster', ['type' => 'checkbox']),
      ];

      $table_data = [];

      $sql_query = $db->query('SHOW TABLE STATUS');
      while ( $sql = $sql_query->fetch_assoc() ) {
        $table_data[] = [
          htmlspecialchars($sql['Name']),
          htmlspecialchars($sql['Rows']),
          round(($sql['Data_length'] + $sql['Index_length']) / 1024 / 1024, 2) . 'M',
          htmlspecialchars($sql['Engine']),
          htmlspecialchars($sql['Collation']),
          (new Tickable('id[]', ['type' => 'checkbox']))->set('value', $sql['Name']),
        ];
      }
  }

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <?php
    if ( isset($action) ) {
      echo '<div class="col-sm-4 text-right align-self-center">';
        echo (new Button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light'))->set_link(
          Guarantor::ensure_global('Admin')->link('database_tables.php'));
      echo '</div>';
    }
    ?>
  </div>

  <?= new Form('sql', Guarantor::ensure_global('Admin')->link('database_tables.php')) ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <?php
          foreach ( $table_headers as $th ) {
            echo '<th>', $th, '</th>';
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ( $table_data as $td ) {
          echo '<tr>';

          foreach ( $td as $data ) {
            echo '<td>', $data, '</td>';
          }

          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>

<?php
if ( !isset($_POST['dryrun']) ) {
 ?>

  <div class="row">
    <div class="col">
      <?=
        new Select('action', $actions, ['id' => 'sqlActionsMenu'])
      . new Button(BUTTON_ACTION_GO, 'fas fa-cogs', 'btn-success btn-block mt-2')
      ?>
    </div>
    <div class="col">
      <span class="runUtf8" style="display: none;"><?= new Select('from_charset', $mysql_charsets) . '<br>' . sprintf(ACTION_UTF8_DRY_RUN, new Tickable('dryrun', ['type' => 'checkbox'])) ?></span>
    </div>
  </div>

  <?php
}
?>

</form>

<script>
$(function() {
  if ( $('form[name="sql"] input[type="checkbox"][name="masterblaster"]').length > 0 ) {
    $('form[name="sql"] input[type="checkbox"][name="masterblaster"]').click(function() {
      $('form[name="sql"] input[type="checkbox"][name="id[]"]').prop('checked', $('form[name="sql"] input[type="checkbox"][name="masterblaster"]').prop('checked'));
    });
  }

  if ( $('#sqlActionsMenu').val() == 'utf8' ) {
    $('.runUtf8').show();
  }

  $('#sqlActionsMenu').change(function() {
    var selected = $(this).val();

    if ( selected == 'utf8' ) {
      $('.runUtf8').show();
    } else {
      $('.runUtf8').hide();
    }
  });
});
</script>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
