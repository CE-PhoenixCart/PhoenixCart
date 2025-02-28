<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $zInfo = $GLOBALS['table_definition']['info'];
  $heading = TEXT_INFO_HEADING_EDIT_ZONE;
  $link = $GLOBALS['link']->set_parameter('zID', (int)$zInfo->geo_zone_id);

  $contents = ['form' => new Form('zones', (clone $link)->set_parameter('action', 'save_zone'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_ZONE_INTRO];
  $contents[] = ['text' => TEXT_INFO_ZONE_NAME . '<br>' . new Input('geo_zone_name', ['value' => $zInfo->geo_zone_name])];
  $contents[] = ['text' => sprintf(TEXT_INFO_ZONE_DESCRIPTION, null) . '<br>' . new Input('geo_zone_description', ['value' => $zInfo->geo_zone_description])];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
