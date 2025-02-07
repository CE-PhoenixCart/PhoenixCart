<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']->manufacturers_id)) {
    $mInfo = &$table_definition['info'];
    $link = $GLOBALS['link']->set_parameter('mID', (int)$mInfo->manufacturers_id);
    $heading = $mInfo->manufacturers_name;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', (clone $link)->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_DATE_ADDED, Date::abridge($mInfo->date_added))];
    if (!Text::is_empty($mInfo->last_modified)) {
      $contents[] = ['text' => sprintf(TEXT_LAST_MODIFIED, Date::abridge($mInfo->last_modified))];
    }
    $contents[] = ['text' => $GLOBALS['Admin']->catalog_image("images/{$mInfo->manufacturers_image}", [], $mInfo->manufacturers_name)];
    
    $contents[] = ['text' => sprintf(TEXT_MANUFACTURERS_ADDRESS, nl2br($mInfo->manufacturers_address) ?? TEXT_NA)];
    $contents[] = ['text' => sprintf(TEXT_MANUFACTURERS_EMAIL, $mInfo->manufacturers_email ?? TEXT_NA)];
    
    $contents[] = ['text' => sprintf(TEXT_PRODUCTS, $mInfo->products_count)];
  }
