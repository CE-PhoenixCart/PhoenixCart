<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $modules = Guarantor::ensure_global('cfg_modules')->getAll();

  $set = (empty($_GET['set']) || !$cfg_modules->exists($_GET['set']))
       ? $modules[0]['code']
       : $_GET['set'];

  $module_type = $cfg_modules->get($set, 'code');
  $module_directory = $cfg_modules->get($set, 'directory');
  $module_language_directory = $cfg_modules->get($set, 'language_directory')
    . "{$_SESSION['language']}/modules/$module_type/";
  $module_key = $cfg_modules->get($set, 'key');
  define('HEADING_TITLE', $cfg_modules->get($set, 'title'));
  $template_integration = $cfg_modules->get($set, 'template_integration');
  
  $get_help_link = $cfg_modules->get($set, 'get_help_link');
  
  $get_addons_link = '';
  if (!empty($cfg_modules->get($set, 'get_addons_links'))) {
    $get_addons_link .= '<div class="btn-group" role="group">';
      $get_addons_link .= '<button type="button" class="btn btn-dark me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
        $get_addons_link .= GET_ADDONS;
      $get_addons_link .= '</button>';
      $get_addons_link .= '<div class="dropdown-menu">';
      foreach ($cfg_modules->get($set, 'get_addons_links') as $k => $v) {
        $get_addons_link .= '<a class="dropdown-item" target="_blank" href="' . $v . '">' . $k . '</a>';
      }
      $get_addons_link .= '</div>';
    $get_addons_link .= '</div>';
  }

  $modules_installed = (defined($module_key) && constant($module_key)) ? explode(';', constant($module_key)) : [];
  $link = $Admin->link()->retain_query_except(['action', 'module'])->set_parameter('set', $set);

  $module_files = cfg_modules::list_modules($set);

  require 'includes/segments/process_action.php';

  $table_definition = [
    'columns' => [
      [
        'name' => TABLE_HEADING_MODULES,
        'is_heading' => true,
        'function' => function ($row) {
          return $row['title'];
        },
      ],
      [
        'name' => TABLE_HEADING_ENABLED,
        'class' => 'text-end',
        'function' => function ($row) {
          return ($row['enabled'] > 0)
               ? '<i class="fas fa-check-circle text-success"></i>'
               : '<i class="fas fa-times-circle text-danger"></i>';
        },
      ],
      [
        'name' => TABLE_HEADING_ACTION,
        'class' => 'text-end',
        'function' => function ($row) {
          return isset($row['info']->code)
               ? '<i class="fas fa-chevron-circle-right text-info"></i>'
               : '<a href="' . $row['onclick'] . '"><i class="fas fa-info-circle text-muted"></i></a>';
        },
      ],
    ],
    'count_text' => '',
    'page' => $_GET['page'] ?? null,
    'web_id' => 'module',
    'db_id' => 'code',
    'rows_per_page' => MAX_DISPLAY_SEARCH_RESULTS,
  ];

  if (!isset($_GET['list']) || ('new' !== $_GET['list'])) {
    array_splice($table_definition['columns'], -1, 0, [
      [
        'name' => TABLE_HEADING_SORT_ORDER,
        'class' => 'text-end',
        'function' => function ($row) {
          return is_numeric($row['sort_order']) ? $row['sort_order'] : '';
        },
      ],
    ]);
  }

  $table_definition['function'] = function (&$row) use (&$table_definition) {
    $GLOBALS['link']->set_parameter('module', $row['code']);

    if (!isset($table_definition['info'])
      && (!isset($_GET['module']) || ($_GET['module'] == $row['code'])))
    {
      $row['keys'] = cfg_modules::build_keys(new $row['code']());
      $table_definition['info'] = new objectInfo($row);
      $row['info'] = &$table_definition['info'];

      $row['css'] = ' class="table-active"';
      $row['onclick'] = (isset($_GET['list']) && ('new' === $_GET['list']))
                      ? null
                      : (clone $GLOBALS['link'])->set_parameter('action', 'edit');
    } else {
      $row['css'] = '';
      $row['onclick'] = $GLOBALS['link'];
    }
  };

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
<?php
  $cfgm = "uncallable_cfgm_$set";
  if (is_callable([$cfgm, 'menu'])) {
?>
    <div class="col text-end align-self-center"><?= $cfgm::menu() ?></div>
<?php
  }
?>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
      $get_addons_link,
      $Admin->button(GET_HELP, '', 'btn-dark me-2', $get_help_link, ['newwindow' => true]),
      $admin_hooks->cat('extraButtons'),
      isset($_GET['list'])
      ? $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', (clone $GLOBALS['link'])->delete_parameter('list'))
      : $Admin->button(IMAGE_MODULE_INSTALL . ' (' . count($module_files['new']) . ')', 'fas fa-plus', 'btn-danger', (clone $GLOBALS['link'])->set_parameter('list', 'new'))
      ?>
    </div>
  </div>

  <div class="row g-0">
    <div class="col-12 col-sm-8">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-dark">
            <tr>
<?php
  foreach ($table_definition['columns'] as $column) {
    echo '              <th';
    if (isset($column['class'])) {
      echo ' class="', $column['class'], '"';
    }
    echo '>', $column['name'], '</th>', PHP_EOL;
  }
?>
            </tr>
          </thead>
          <tbody>
<?php
  foreach ($module_files[(isset($_GET['list']) && ('new' === $_GET['list'])) ? 'new' : 'installed'] as $row) {
    $table_definition['function']($row);

    $row_attributes = $row['css'];
    if (isset($row['onclick'])) {
      $row_attributes .= <<<"EOJS"
 onclick="document.location.href='{$row['onclick']}'"
EOJS;
    }
?>
            <tr<?= $row_attributes ?>>
<?php
    foreach ($table_definition['columns'] as $column) {
      if ($column['is_heading'] ?? false) {
        echo '              <th scope="row"';
        $close = '</th>';
      } else {
        echo '              <td';
        $close = '</td>';
      }

      if (isset($column['class'])) {
        echo ' class="', $column['class'], '"';
      }

      echo '>', $column['function']($row), $close, PHP_EOL;
    }
?>
            </tr>
<?php
  }
?>
          </tbody>
        </table>
      </div>
      <p><?= TEXT_MODULE_DIRECTORY . ' ' . $module_directory ?></p>
      <?= $GLOBALS['admin_hooks']->cat($table_definition['hooks']['button'] ?? 'buttons') ?>
    </div>

<?php
  if (!isset($_GET['list'])) {
    if (defined($module_key)) {
      $cfg_modules->fix_installed_constant($set, $module_files['installed']);
    } else {
      $db->query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', '" . $db->escape($module_key) . "', '', 'This is automatically updated. No need to edit.', 6, 0, NOW())");
    }

    if ($template_integration) {
      if (defined('TEMPLATE_BLOCK_GROUPS')) {
        $tbgroups = explode(';', TEMPLATE_BLOCK_GROUPS);
        if (!in_array($module_type, $tbgroups)) {
          $tbgroups[] = $module_type;
          sort($tbgroups);
          $db->query("UPDATE configuration SET configuration_value = '" . $db->escape(implode(';', $tbgroups)) . "', last_modified = NOW() WHERE configuration_key = 'TEMPLATE_BLOCK_GROUPS'");
        }
      } else {
        $db->query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Template Block Groups', 'TEMPLATE_BLOCK_GROUPS', '" . $db->escape($module_type) . "', 'This is automatically updated. No need to edit.', 6, 0, NOW())");
      }
    }
  }

  if ($action_file = $Admin->locate('/infoboxes', $action)) {
    require DIR_FS_ADMIN . 'includes/components/infobox.php';
  }
?>

  </div>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
