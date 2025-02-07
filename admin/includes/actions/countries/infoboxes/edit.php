<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (!isset($GLOBALS['table_definition']['info']->countries_id)) {
    error_log('Nothing selected for editing');
    return;
  }

  $cInfo =& $GLOBALS['table_definition']['info'];
  $heading = TEXT_INFO_HEADING_EDIT_COUNTRY;

  $link = $GLOBALS['link']->set_parameter('cID', $cInfo->countries_id);

  $select = new Select('address_format_id', $GLOBALS['db']->fetch_all("SELECT address_format_id AS id, address_summary AS text FROM address_format ORDER BY address_format_id"), ['class' => 'form-select']);

  $contents = ['form' => new Form('currencies', (clone $link)->set_parameter('action', 'save'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];

  $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_NAME, null) . '<br>' . (new Input('countries_name', ['value' => $cInfo->countries_name]))->require()];
  $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_CODE_2, null)  . '<br>' . (new Input('countries_iso_code_2', ['value' => $cInfo->countries_iso_code_2]))->require()];
  $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_CODE_3, null)  . '<br>' . (new Input('countries_iso_code_3', ['value' => $cInfo->countries_iso_code_3]))->require()];
  $contents[] = ['text' => sprintf(TEXT_INFO_ADDRESS_FORMAT, null)  . '<br>' . $select->require()->set_selection($cInfo->address_format_id)];

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
