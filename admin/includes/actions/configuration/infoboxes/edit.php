<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $cInfo = &$GLOBALS['table_definition']['info'];
  $heading = $cInfo->configuration_title;
  $GLOBALS['link']->set_parameter('cID', (int)$cInfo->configuration_id);

  if ($cInfo->set_function) {
    eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
  } else {
    $value_field = new Input('configuration_value', ['value' => $cInfo->configuration_value]);
  }

  $contents = ['form' => new Form('configuration', (clone $GLOBALS['link'])->set_parameter('action', 'save'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
  $contents[] = ['text' => '<strong>' . $cInfo->configuration_title . '</strong><br>' . $cInfo->configuration_description . '<br>' . $value_field];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
