<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->configuration_id)) {
    $cInfo = &$GLOBALS['table_definition']['info'];
    $GLOBALS['link']->set_parameter('cID', (int)$cInfo->configuration_id);
    $heading = $cInfo->configuration_title;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', $GLOBALS['link']->set_parameter('action', 'edit')),
    ];
    $contents[] = ['text' => $cInfo->configuration_description];
    $contents[] = ['text' => TEXT_INFO_DATE_ADDED . ' ' . Date::abridge($cInfo->date_added)];
    if (!Text::is_empty($cInfo->last_modified)) {
      $contents[] = ['text' => TEXT_INFO_LAST_MODIFIED . ' ' . Date::abridge($cInfo->last_modified)];
    }
  }
