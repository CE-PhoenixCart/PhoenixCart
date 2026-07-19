<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  if (!isset($_GET['tpl'])) {
    $action = '';
    return;
  }
  
  // ensure templates  
  $templates = [];
  foreach (scandir(DIR_FS_CATALOG . 'templates', SCANDIR_SORT_ASCENDING) as $template) {
    if ('.' !== $template[0]) {
      $templates[] = $template;
    }
  }
      
  $tpl = Text::input($_GET['tpl']);

  if (in_array($tpl, $templates)) {
    $db->query("UPDATE configuration SET configuration_value = '$tpl' where configuration_key = 'TEMPLATE_SELECTION'");

    $messageStack->add_session(sprintf(TEMPLATE_CHANGE_SUCCESSFUL, strtoupper($tpl), $GLOBALS['Admin']->catalog('index.php')), 'success');
  }
  else {
    $messageStack->add_session(TEMPLATE_CHANGE_UNSUCCESSFUL, 'danger');
  }
  
  return $Admin->link('templates.php');
