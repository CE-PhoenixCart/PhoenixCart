<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_EDIT_TAX_RATE;
  $trInfo = &$GLOBALS['table_definition']['info'];
  $GLOBALS['link']->set_parameter('tID', $trInfo->tax_rates_id);

  $contents = ['form' => new Form('rates', (clone $GLOBALS['link'])->set_parameter('action', 'save'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
  $contents[] = ['text' => TEXT_INFO_CLASS_TITLE . '<br>' . (new Select('tax_class_id', Tax::fetch_classes(), ['class' => 'form-select']))->set_selection($trInfo->tax_class_id)];
  $contents[] = ['text' => TEXT_INFO_ZONE_NAME . '<br>' . (new Select('tax_zone_id', geo_zone::fetch_options(), ['class' => 'form-select']))->set_selection($trInfo->geo_zone_id)];
  $contents[] = ['text' => TEXT_INFO_TAX_RATE . '<br>' . new Input('tax_rate', ['value' => $trInfo->tax_rate])];
  $contents[] = ['text' => sprintf(TEXT_INFO_RATE_DESCRIPTION, null) . '<br>' . new Input('tax_description', ['value' => $trInfo->tax_description])];
  $contents[] = ['text' => TEXT_INFO_TAX_RATE_PRIORITY . '<br>' . new Input('tax_priority', ['value' => $trInfo->tax_priority])];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
