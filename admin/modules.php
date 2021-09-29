<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

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

  $modules_installed = (defined($module_key) && constant($module_key)) ? explode(';', constant($module_key)) : [];

  require 'includes/segments/process_action.php';

  $new_modules_counter = 0;

  $module_files = [];
  if ($dir = @dir($module_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        if (isset($_GET['list']) && ('new' === $_GET['list'])) {
          if (!in_array($file, $modules_installed)) {
            $module_files[] = $file;
          }
        } else {
          if (in_array($file, $modules_installed)) {
            $module_files[] = $file;
          } else {
            $new_modules_counter++;
          }
        }
      }
    }
    sort($module_files);
    $dir->close();
  }

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE; ?></h1>
    </div>
    <div class="col-sm-4 text-right align-self-center">
      <?=
      isset($_GET['list'])
      ? $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $Admin->link('modules.php', ['set' => $set]))
      : $Admin->button(IMAGE_MODULE_INSTALL . ' (' . $new_modules_counter . ')', 'fas fa-plus', 'btn-danger', $Admin->link('modules.php', ['set' => $set, 'list' => 'new']))
      ?>
    </div>
  </div>

  <div class="row no-gutters">
    <div class="col-12 col-sm-8">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th><?= TABLE_HEADING_MODULES ?></th>
              <th class="text-right"><?= TABLE_HEADING_SORT_ORDER ?></th>
              <th class="text-right"><?= TABLE_HEADING_ACTION ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $installed_modules = [];
            foreach ($module_files as $file) {
              $class = pathinfo($file, PATHINFO_FILENAME);
              if (class_exists($class)) {
                $module = new $class();
                if ($module->check() > 0) {
                  if (($module->sort_order > 0) && !isset($installed_modules[$module->sort_order])) {
                    $installed_modules[$module->sort_order] = $file;
                  } else {
                    $installed_modules[] = $file;
                  }
                }

                if (!isset($mInfo) && (!isset($_GET['module']) || ($_GET['module'] == $class))) {
                  $module_info = [
                    'code' => $module->code,
                    'title' => $module->title,
                    'description' => $module->description,
                    'status' => $module->check(),
                    'signature' => ($module->signature ?? null),
                    'api_version' => ($module->api_version ?? null),
                  ];

                  $keys_extra = [];
                  foreach ($module->keys() as $key) {
                    $key_value_query = $db->query("SELECT configuration_title, configuration_value, configuration_description, use_function, set_function FROM configuration WHERE configuration_key = '" . $key . "'");
                    $key_value = $key_value_query->fetch_assoc();

                    if (!isset($keys_extra[$key])) {
                      $keys_extra[$key] = [];
                    }

                    if (is_null($key_value) && ($module->check() <= 0)) {
                      continue;
                    }

                    $keys_extra[$key]['title'] = $key_value['configuration_title'];
                    $keys_extra[$key]['value'] = $key_value['configuration_value'];
                    $keys_extra[$key]['description'] = $key_value['configuration_description'];
                    $keys_extra[$key]['use_function'] = $key_value['use_function'];
                    $keys_extra[$key]['set_function'] = $key_value['set_function'];
                  }

                  $module_info['keys'] = $keys_extra;

                  $mInfo = new objectInfo($module_info);
                }

                $link = $Admin->link('modules.php', ['set' => $set, 'module' => $class]);
                if (isset($mInfo->code) && ($class == $mInfo->code) ) {
                  if ($module->check() > 0) {
                    echo '<tr class="table-active onclick="document.location.href=\'' . $link->set_parameter('action', 'edit') . '\'">' . PHP_EOL;
                  } else {
                    echo '<tr class="table-active">' . PHP_EOL;
                  }

                  $icon = '<i class="fas fa-chevron-circle-right text-info"></i>';
                } else {
                  if (isset($_GET['list'])) {
                    $link->set_parameter('list', 'new');
                  }
                  echo '<tr onclick="document.location.href=\'' . $link . '\'">' . PHP_EOL;
                  $icon = '<a href="' . $link . '"><i class="fas fa-info-circle text-muted"></i></a>';
                }
                ?>
                <td><?= $module->title ?></td>
                <td class="text-right"><?php if (in_array($module->code . ".php", $modules_installed) && is_numeric($module->sort_order)) echo $module->sort_order; ?></td>
                <td class="text-right"><?= $icon ?></td>
              </tr>
              <?php
              }
            }

            if (!isset($_GET['list'])) {
              ksort($installed_modules);
              $check_query = $db->query("SELECT configuration_value FROM configuration WHERE configuration_key = '" . $module_key . "'");
              if ($check = $check_query->fetch_assoc()) {
                if ($check['configuration_value'] != implode(';', $installed_modules)) {
                  $db->query("UPDATE configuration SET configuration_value = '" . implode(';', $installed_modules) . "', last_modified = NOW() WHERE configuration_key = '" . $module_key . "'");
                }
              } else {
                $db->query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', '" . $module_key . "', '" . implode(';', $installed_modules) . "', 'This is automatically updated. No need to edit.', '6', '0', NOW())");
              }

              if ($template_integration) {
                $check_query = $db->query("SELECT configuration_value FROM configuration WHERE configuration_key = 'TEMPLATE_BLOCK_GROUPS'");
                if ($check = $check_query->fetch_assoc()) {
                  $tbgroups = explode(';', $check['configuration_value']);
                  if (!in_array($module_type, $tbgroups)) {
                    $tbgroups[] = $module_type;
                    sort($tbgroups);
                    $db->query("UPDATE configuration SET configuration_value = '" . implode(';', $tbgroups) . "', last_modified = NOW() WHERE configuration_key = 'TEMPLATE_BLOCK_GROUPS'");
                  }
                } else {
                  $db->query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Template Block Groups', 'TEMPLATE_BLOCK_GROUPS', '" . $module_type . "', 'This is automatically updated. No need to edit.', '6', '0', NOW())");
                }
              }
            }
            ?>
          </tbody>
        </table>
      </div>
      <p><?= TEXT_MODULE_DIRECTORY . ' ' . $module_directory; ?></p>
    </div>

<?php
  if ($action_file = $GLOBALS['Admin']->locate('/infoboxes', $GLOBALS['action'])) {
    require DIR_FS_ADMIN . 'includes/components/infobox.php';
  }
?>

  </div>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
