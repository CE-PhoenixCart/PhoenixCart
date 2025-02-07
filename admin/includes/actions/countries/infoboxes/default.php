<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->countries_id)) {
    $cInfo =& $GLOBALS['table_definition']['info'];
    $heading = $cInfo->countries_name;

    $link = $GLOBALS['link']->set_parameter('cID', $cInfo->countries_id);

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $link->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_NAME, $cInfo->countries_name)];
    $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_CODE_2, $cInfo->countries_iso_code_2)];
    $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY_CODE_3, $cInfo->countries_iso_code_3)];
    $contents[] = ['text' => sprintf(TEXT_INFO_ADDRESS_FORMAT, $cInfo->address_format_id)];
  }
