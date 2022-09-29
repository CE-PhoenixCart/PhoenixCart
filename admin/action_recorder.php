<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $always_valid_actions = ['expire'];
  require 'includes/application_top.php';
  class_exists('abstract_page_cfgm');

  $classes = [];
  if ($dir = @dir(DIR_FS_CATALOG . 'includes/modules/action_recorder/')) {
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_CATALOG . 'includes/modules/action_recorder/' . $file)) {
        if ('php' === pathinfo($file, PATHINFO_EXTENSION)) {
          $classes[] = pathinfo($file, PATHINFO_FILENAME);
        }
      }
    }
    $dir->close();
    sort($classes);
  }

  foreach (array_filter($classes, 'class_exists') as $class) {
    ${$class} = new $class();
  }

  $modules_list = [['id' => '', 'text' => TEXT_ALL_MODULES]];

  $modules = array_column($db->fetch_all("SELECT DISTINCT module FROM action_recorder ORDER BY module"), 'module');
  foreach ($modules as $module) {
    $modules_list[] = [
      'id' => $module,
      'text' => (${$module}->title ?? $module),
    ];
  }

  require 'includes/segments/process_action.php';

  $delete_link = $Admin->link('action_recorder.php', ['action' => 'expire']);
  if (isset($_GET['module']) && in_array($_GET['module'], $modules)) {
    $delete_link->set_parameter('module', $_GET['module']);
  }

  $filter = [];

  if (isset($_GET['module'])) {
    if (in_array($_GET['module'], $modules)) {
      $filter[] = "module = '" . $db->escape($_GET['module']) . "'";
    } else {
      unset($_GET['module']);
    }
  }

  if (!empty($_GET['search'])) {
    $filter[] = "identifier LIKE '%" . $db->escape($_GET['search']) . "%'";
  }

  $action_recorder_sql = "SELECT * FROM action_recorder";
  if (count($filter) > 0) {
    $action_recorder_sql .= " WHERE " . implode(" AND ", $filter);
  }
  $action_recorder_sql .= " ORDER BY date_added DESC";
  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_MODULE,
        'function' => function (&$row) {
          return $row['module'];
        },
      ],
      [
        'name' => TABLE_HEADING_CUSTOMER,
        'function' => function (&$row) {
          return htmlspecialchars($row['user_name']) . ' [' . (int)$row['user_id'] . ']';
        },
      ],
      [
        'name' => TABLE_HEADING_SUCCESS,
        'function' => function (&$row) {
          return ($row['success'] == '1')
               ? '<i class="fas fa-check-circle text-success"></i>'
               : '<i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_DATE_ADDED,
        'class' => 'text-right',
        'function' => function (&$row) {
          return (new Date($row['date_added']))->format(DATE_TIME_FORMAT);
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-right',
        'function' => function ($row) {
          return (isset($row['info']->id) && ($row['info']->id === $row['id']))
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => TEXT_DISPLAY_NUMBER_OF_ENTRIES,
    'page' => $_GET['page'] ?? null,
    'sql' => $action_recorder_sql,
  ];
  $table_definition['split'] = new Paginator($table_definition);
  $table_definition['function'] = function (&$row) use (&$table_definition) {
    if (isset($GLOBALS[$row['module']]->title)) {
      $row['module'] = $GLOBALS[$row['module']]->title;
    }

    $link = $GLOBALS['Admin']->link()->retain_query_except(['action'])->set_parameter('aID', $row['id']);
    if (!isset($table_definition['info']) && (!isset($_GET['aID']) || ($_GET['aID'] === $row['id']))) {
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['onclick'] = $link->set_parameter('action', 'edit');
      $row['css'] = ' class="table-active"';
    } else {
      $row['onclick'] = $link;
      $row['css'] = '';
    }
  };

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col-12 col-sm-6">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-8 col-sm-4">
      <?=
      (new Form('search', $Admin->link('action_recorder.php'), 'get'))->hide_session_id()->hide('module', ''),
        new Input('search', ['placeholder' => TEXT_FILTER_SEARCH, 'class' => 'form-control form-control-sm mb-1']),
      '</form>',
      (new Form('filter', $Admin->link('action_recorder.php'), 'get'))->hide_session_id()->hide('module', ''),
        new Select('module', $modules_list, ['onchange' => 'this.form.submit();', 'class' => 'form-control form-control-sm']),
      '</form>'
      ?>
    </div>
    <div class="col-4 col-sm-2">
      <?= $Admin->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger btn-block btn-sm', $delete_link) ?>
    </div>
  </div>

<?php
  $table_definition['split']->display_table();

  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
