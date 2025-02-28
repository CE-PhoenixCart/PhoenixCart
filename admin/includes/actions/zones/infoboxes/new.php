<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_NEW_ZONE;

  $contents = ['form' => new Form('zones', $GLOBALS['Admin']->link()->set_parameter('action', 'insert'))];
  $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
  $contents[] = ['text' => TEXT_INFO_ZONES_NAME . '<br>' . new Input('zone_name')];
  $contents[] = ['text' => TEXT_INFO_ZONES_CODE . '<br>' . new Input('zone_code')];
  $contents[] = ['text' => TEXT_INFO_COUNTRY_NAME . '<br>' . new Select('zone_country_id', Country::fetch_options(), ['class' => 'form-select'])];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
