<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']->tax_class_id)) {
    $tcInfo = &$table_definition['info'];
    $GLOBALS['link']->set_parameter('tID', $tcInfo->tax_class_id);
    $heading = $tcInfo->tax_class_title;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $GLOBALS['link'])->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', $GLOBALS['link']->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE_ADDED, Date::abridge($tcInfo->date_added))];
    $contents[] = ['text' => sprintf(TEXT_INFO_LAST_MODIFIED, Date::abridge($tcInfo->last_modified))];
    $contents[] = ['text' => sprintf(TEXT_INFO_CLASS_DESCRIPTION, $tcInfo->tax_class_description)];
  }
