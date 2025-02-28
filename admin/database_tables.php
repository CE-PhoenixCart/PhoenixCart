<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $passthrough_actions = ['check', 'analyze', 'optimize', 'repair'];
  $mysql_charsets = [['id' => 'auto', 'text' => ACTION_UTF8_CONVERSION_FROM_AUTODETECT]];

  $charsets_query = $db->query('SHOW CHARACTER SET');
  while ( $charset = $charsets_query->fetch_assoc() ) {
    $mysql_charsets[] = ['id' => $charset['Charset'], 'text' => sprintf(ACTION_UTF8_CONVERSION_FROM, $charset['Charset'])];
  }

  $command = '';
  $admin_hooks->set('preAction', '__filter_tables', function () {
    if (!isset($_POST['action'])) {
      return;
    }

    if (in_array($_POST['action'], $GLOBALS['passthrough_actions'])) {
      $GLOBALS['command'] = $GLOBALS['db']->escape(strtoupper(Text::input($_POST['action'])));
      $GLOBALS['action'] = 'passthrough';
      $_POST['action'] = $GLOBALS['action'];
    } else {
      $GLOBALS['action'] = $_POST['action'];

      if ('utf8' !== $_POST['action']) {
        return;
      }
    }

    if (empty($_POST['id']) || !is_array($_POST['id'])
     || !($_POST['id'] = array_intersect($_POST['id'], array_column($GLOBALS['db']->fetch_all('SHOW TABLE STATUS'), 'Name'))))
    {
      $GLOBALS['action'] = '';
    }
  });

  $masterblaster = new Tickable('masterblaster', ['onchange' => 'toggle_all(this)'], 'checkbox');

  require 'includes/segments/process_action.php';

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

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $Admin->button(GET_HELP, '', 'btn-dark', GET_HELP_LINK, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      empty($action) ? '' : (new Button(IMAGE_BACK, 'fas fa-angle-left', 'ms-2 btn-light'))->set('href', $Admin->link())
      ?>
    </div>
  </div>

  <?= new Form('sql', $Admin->link()) ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead class="table-dark">
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

  <div class="row mt-2">
    <div class="col d-grid">
      <?=
        new Select('action', $actions, ['class' => 'form-select', 'id' => 'sqlActionsMenu']),
        new Button(BUTTON_ACTION_GO, 'fas fa-cogs', 'btn-success mt-2')
      ?>
    </div>
    <div class="col">
      <span class="runUtf8"><?= new Select('from_charset', $mysql_charsets, ['class' => 'form-select']) . '<br>' . sprintf(ACTION_UTF8_DRY_RUN, new Tickable('dryrun', [], 'checkbox')) ?></span>
    </div>
  </div>

<script>
function when_ready() {
  var toggle = function (display) {
    var runners = document.querySelectorAll('.runUtf8');
    for (var i = 0; i < runners.length; i++) {
      runners[i].style.display = display;
    }
  }

  var actions_menu = document.querySelector('#sqlActionsMenu');
  if (actions_menu) {
    if ('utf8' !== actions_menu.value) {
      toggle('none');
    }

    actions_menu.addEventListener('change', function (e) {
      toggle(('utf8' === e.target.value) ? '' : 'none');
    });
  }
}

document.addEventListener('DOMContentLoaded', when_ready);
if (document.readyState === 'interactive' || document.readyState === 'complete' ) {
  when_ready();
}
</script>
  <?php
  }
?>

</form>

<script>
function toggle_all(source) {
  checkboxes = document.getElementsByName('id[]');
  for (var i=0; i < checkboxes.length; i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
