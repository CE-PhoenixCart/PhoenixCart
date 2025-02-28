<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->zone_id)) {
    $cInfo =& $GLOBALS['table_definition']['info'];
    $GLOBALS['link']->set_parameter('cID', $cInfo->zone_id);

    $heading = $cInfo->zone_name;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $GLOBALS['link'])->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $GLOBALS['link']->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => TEXT_INFO_ZONES_NAME . '<br>' . $cInfo->zone_name . ' (' . $cInfo->zone_code . ')'];
    $contents[] = ['text' => TEXT_INFO_COUNTRY_NAME . ' ' . $cInfo->countries_name];
  }
