<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->geo_zone_id)) {
    $zInfo = $GLOBALS['table_definition']['info'];
    $heading = $zInfo->geo_zone_name;
    $link = $GLOBALS['link']->set_parameter('zID', $zInfo->geo_zone_id);

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit_zone'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', (clone $link)->set_parameter('action', 'delete_zone'))
              . $GLOBALS['Admin']->button(IMAGE_DETAILS, 'fas fa-eye', 'btn-info', $link->set_parameter('action', 'list'))
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_NUMBER_ZONES, $zInfo->num_zones)];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE_ADDED, Date::abridge($zInfo->date_added))];
    if (!Text::is_empty($zInfo->last_modified)) {
      $contents[] = ['text' => sprintf(TEXT_INFO_LAST_MODIFIED, Date::abridge($zInfo->last_modified))];
    }
    $contents[] = ['text' => sprintf(TEXT_INFO_ZONE_DESCRIPTION, $zInfo->geo_zone_description)];
  }
