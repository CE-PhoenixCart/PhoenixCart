<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (is_object($table_definition['info'] ?? null)) {
    $sInfo = $table_definition['info'];
    $heading = $sInfo->products_name;

    $link = $GLOBALS['Admin']->link('specials.php')->retain_query_except()->set_parameter('sID', $sInfo->specials_id);
    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', $link->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => TEXT_INFO_DATE_ADDED . ' ' . Date::abridge($sInfo->specials_date_added)];
    $contents[] = ['text' => TEXT_INFO_LAST_MODIFIED . ' ' . Date::abridge($sInfo->specials_last_modified)];
    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->catalog_image("images/{$sInfo->products_image}", [], $sInfo->products_name),
    ];
    $contents[] = ['text' => TEXT_INFO_ORIGINAL_PRICE . ' ' . $GLOBALS['currencies']->format($sInfo->products_price)];
    $contents[] = ['text' => TEXT_INFO_NEW_PRICE . ' ' . $GLOBALS['currencies']->format($sInfo->specials_new_products_price)];
    $contents[] = ['text' => TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($sInfo->specials_new_products_price / $sInfo->products_price) * 100)) . '%'];

    if (!empty($sInfo->expires_date)) $contents[] = ['text' => TEXT_INFO_EXPIRES_DATE . ' ' . Date::abridge($sInfo->expires_date)];
    if (!empty($sInfo->date_status_change)) $contents[] = ['text' => TEXT_INFO_STATUS_CHANGE . ' ' . Date::abridge($sInfo->date_status_change)];
  }
