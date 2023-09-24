<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (class_exists($_GET['module'])) {
    $module =& Guarantor::ensure_global($_GET['module']);

    if (cfg_modules::can($module, 'remove')) {
      $module->remove();

      cfg_modules::update_configuration(
        $db->escape(implode(';', array_diff($modules_installed, ["{$_GET['module']}.php"]))),
        $db->escape($module_key));

      return $link;
    }

    $messageStack->add_session(ERROR_MODULE_HAS_DEPENDENTS, 'error');
    foreach ($customer_data->get_last_matched_requirers() as $requirement => $requirers) {
      $messageStack->add_session($requirement . htmlspecialchars(' => ') . implode(', ', $requirers));
    }
  }

  Href::redirect($link);
