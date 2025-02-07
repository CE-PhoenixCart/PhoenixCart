<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/


  $heading = TEXT_INFO_HEADING_NEW_COUNTRY;

  $address_formats = $GLOBALS['db']->fetch_all("SELECT address_format_id AS id, address_summary AS text FROM address_format ORDER BY address_format_id");

  $contents = ['form' => new Form('countries', (clone $GLOBALS['link'])->set_parameter('action', 'insert'))];
  $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];

  $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_NAME, null) . '<br>' . (new Input('countries_name'))->require()];
  $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_CODE_2, null)  . '<br>' . (new Input('countries_iso_code_2'))->require()];
  $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_CODE_3, null)  . '<br>' . (new Input('countries_iso_code_3'))->require()];
  $contents[] = ['text' => sprintf(TEXT_INFO_ADDRESS_FORMAT, null)  . '<br>' . (new Select('address_format_id', $address_formats, ['class' => 'form-select']))->require()];


  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
