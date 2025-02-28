<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->tax_rates_id)) {
    $trInfo = &$GLOBALS['table_definition']['info'];

    $heading = $trInfo->tax_class_title;
    $GLOBALS['link']->set_parameter('tID', $trInfo->tax_rates_id);
    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $GLOBALS['link'])->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', $GLOBALS['link']->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE_ADDED, Date::abridge($trInfo->date_added))];
    $contents[] = ['text' => sprintf(TEXT_INFO_LAST_MODIFIED, Date::abridge($trInfo->last_modified))];
    $contents[] = ['text' => sprintf(TEXT_INFO_RATE_DESCRIPTION, $trInfo->tax_description)];
  }
