<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (is_object($GLOBALS['table_definition']['info'] ?? null)) {
    $cInfo =& $GLOBALS['table_definition']['info'];
    $heading = $cInfo->title;

    $link = $GLOBALS['Admin']->link('currencies.php', ['cID' => $cInfo->currencies_id]);
    if (isset($_GET['page'])) {
      $link->set_parameter('page', (int)$_GET['page']);
    }

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $link->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_TITLE, $cInfo->title)];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_CODE, $cInfo->code)];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_SYMBOL_LEFT, $cInfo->symbol_left)];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_SYMBOL_RIGHT, $cInfo->symbol_right)];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_DECIMAL_POINT, $cInfo->decimal_point)];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_THOUSANDS_POINT, $cInfo->thousands_point)];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_DECIMAL_PLACES, $cInfo->decimal_places)];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_LAST_UPDATED, Date::abridge($cInfo->last_updated))];
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_VALUE, number_format($cInfo->value, 8))];

    $currencies =& Guarantor::ensure_global('currencies');
    $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_EXAMPLE, $currencies->format('30', false, DEFAULT_CURRENCY), $currencies->format('30', true, $cInfo->code))];
  }
