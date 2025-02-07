<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $zInfo = $GLOBALS['table_definition']['info'];
  $heading = TEXT_INFO_HEADING_DELETE_ZONE;
  $link = $GLOBALS['link']->set_parameter('zID', (int)$zInfo->geo_zone_id);

  $contents = ['form' => new Form('zones', (clone $link)->set_parameter('action', 'delete_confirm_zone'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_ZONE_INTRO];
  $contents[] = [
    'class' => 'text-center text-uppercase fw-bold',
    'text' => $zInfo->geo_zone_name,
  ];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
