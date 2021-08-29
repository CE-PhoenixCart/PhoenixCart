<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $basename = "{$_GET['module']}.php";
  $link = $Admin->link('modules.php', ['set' => $set, 'module' => $_GET['module']]);
  if (class_exists($_GET['module'])) {
    $module =& Guarantor::ensure_globals($_GET['module']);

    if (cfg_modules::can($module, 'install')) {
      if ($module->check() > 0) {
// remove module if already installed
        $module->remove();
      }

      $module->install();

      if (!in_array($basename, $modules_installed)) {
        $modules_installed[] = $basename;
      }

      $db->query(sprintf(
        "UPDATE configuration SET configuration_value = '%s' WHERE configuration_key = '%s'",
        $db->escape(implode(';', $modules_installed)),
        $db->escape($module_key)));

      return $link;
    }

    $messageStack->add_session(ERROR_MODULE_UNMET_REQUIREMENT, 'error');
    foreach ($customer_data->get_last_missing_abilities() as $missing_ability) {
      $messageStack->add_session($missing_ability);
    }
  }

  Href::redirect($link);
