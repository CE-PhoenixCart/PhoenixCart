<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $tcInfo = &$table_definition['info'];
  $GLOBALS['link']->set_parameter('tID', $tcInfo->tax_class_id);
  $heading = TEXT_INFO_HEADING_EDIT_TAX_CLASS;

  $contents = ['form' => new Form('classes', (clone $GLOBALS['link'])->set_parameter('action', 'save'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
  $contents[] = ['text' => TEXT_INFO_CLASS_TITLE . '<br>' . new Input('tax_class_title', ['value' => $tcInfo->tax_class_title])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CLASS_DESCRIPTION, null) . '<br>' . new Input('tax_class_description', ['value' => $tcInfo->tax_class_description])];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
