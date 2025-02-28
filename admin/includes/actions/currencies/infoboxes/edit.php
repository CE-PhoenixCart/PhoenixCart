<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (!is_object($GLOBALS['table_definition']['info'] ?? null)) {
    error_log('Nothing selected for editing');
    return;
  }

  $cInfo =& $GLOBALS['table_definition']['info'];
  $heading = TEXT_INFO_HEADING_EDIT_CURRENCY;

  $link = $GLOBALS['Admin']->link('currencies.php', ['cID' => $cInfo->currencies_id]);
  if (isset($_GET['page'])) {
    $link->set_parameter('page', (int)$_GET['page']);
  }

  $contents = ['form' => new Form('currencies', (clone $link)->set_parameter('action', 'save'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_TITLE, null) . '<br>' . new Input('title', ['value' => $cInfo->title])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_CODE, null)  . '<br>' . new Input('code', ['value' => $cInfo->code])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_SYMBOL_LEFT, null)  . '<br>' . new Input('symbol_left', ['value' => $cInfo->symbol_left])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_SYMBOL_RIGHT, null)  . '<br>' . new Input('symbol_right', ['value' => $cInfo->symbol_right])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_DECIMAL_POINT, null)  . '<br>' . new Input('decimal_point', ['value' => $cInfo->decimal_point])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_THOUSANDS_POINT, null)  . '<br>' . new Input('thousands_point', ['value' => $cInfo->thousands_point])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_DECIMAL_PLACES, null)  . '<br>' . new Input('decimal_places', ['value' => $cInfo->decimal_places])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_VALUE, null) . '<br>' . new Input('value', ['value' => $cInfo->value])];
  if (DEFAULT_CURRENCY != $cInfo->code) {
    $contents[] = [
      'text' => '<div class="form-check form-switch">'
              . new Tickable('default', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'cDefault'], 'checkbox')
              . '<label for="cDefault" class="form-check-label text-muted"><small>' . TEXT_INFO_SET_AS_DEFAULT . '</small></label></div>',
    ];
  }
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
