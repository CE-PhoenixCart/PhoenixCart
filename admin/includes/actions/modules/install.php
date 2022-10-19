<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $basename = "{$_GET['module']}.php";
  if (class_exists($_GET['module'])) {
    $module =& Guarantor::ensure_global($_GET['module']);

    if (cfg_modules::can($module, 'install')) {
      if ($module->check() > 0) {
// remove module if already installed
        $module->remove();
      }

      $module->install();

      if (!in_array($basename, $modules_installed)) {
        $modules_installed[] = $basename;
      }

      $cfg_modules->fix_installed_constant($set, array_map(function ($m) {
        return ['file' => $m];
      }, $modules_installed));

      return $link->set_parameter('module', $_GET['module']);
    }

    $messageStack->add_session(ERROR_MODULE_UNMET_REQUIREMENT, 'error');
    foreach ($customer_data->get_last_missing_abilities() as $missing_ability) {
      $messageStack->add_session($missing_ability);
    }
  }

  Href::redirect($link);
