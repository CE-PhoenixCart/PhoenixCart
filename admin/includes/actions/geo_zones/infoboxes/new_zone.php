<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_NEW_ZONE;
  $link = $GLOBALS['link'];
  if (isset($_GET['zID'])) {
    $link->set_parameter('zID', (int)$_GET['zID']);
  }

  $contents = ['form' => new Form('zones', (clone $link)->set_parameter('action', 'insert_zone'))];
  $contents[] = ['text' => TEXT_INFO_NEW_ZONE_INTRO];
  $contents[] = ['text' => TEXT_INFO_ZONE_NAME . '<br>' . new Input('geo_zone_name')];
  $contents[] = ['text' => sprintf(TEXT_INFO_ZONE_DESCRIPTION, null) . '<br>' . new Input('geo_zone_description')];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
