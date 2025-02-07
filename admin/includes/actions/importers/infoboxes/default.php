<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']->importers_id)) {
    $iInfo = &$table_definition['info'];
    $link = $GLOBALS['link']->set_parameter('iID', (int)$iInfo->importers_id);
    $heading = $iInfo->importers_name;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', (clone $link)->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_DATE_ADDED, Date::abridge($iInfo->date_added))];
    if (!Text::is_empty($iInfo->last_modified)) {
      $contents[] = ['text' => sprintf(TEXT_LAST_MODIFIED, Date::abridge($iInfo->last_modified))];
    }
    $contents[] = ['text' => $GLOBALS['Admin']->catalog_image("images/{$iInfo->importers_image}", [], $iInfo->importers_name)];
    
    $contents[] = ['text' => sprintf(TEXT_IMPORTERS_ADDRESS, nl2br($iInfo->importers_address) ?? TEXT_NA)];
    $contents[] = ['text' => sprintf(TEXT_IMPORTERS_EMAIL, $iInfo->importers_email ?? TEXT_NA)];
    
    $contents[] = ['text' => sprintf(TEXT_PRODUCTS, $iInfo->products_count)];
  }
