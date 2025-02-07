<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $cInfo =& $GLOBALS['table_definition']['info'];
  $GLOBALS['link']->set_parameter('cID', $cInfo->zone_id);
  $heading = TEXT_INFO_HEADING_DELETE_ZONE;

  $contents = ['form' => new Form('zones', (clone $GLOBALS['link'])->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
  $contents[] = ['text' => '<strong>' . $cInfo->zone_name . '</strong>'];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
