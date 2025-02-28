<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_NEW_CURRENCY;

  $link = $GLOBALS['Admin']->link('currencies.php', ['action' => 'insert']);
  if (isset($_GET['page'])) {
    $link->set_parameter('page', (int)$_GET['page']);
  }
  if (isset($GLOBALS['table_definition']['info']->currencies_id)) {
    $link->set_parameter('cID', $GLOBALS['table_definition']['info']->currencies_id);
  }

  $contents = ['form' => new Form('currencies', $link)];
  $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
  $contents[] = ['text' => new Select('cs', $GLOBALS['currency_select_array'], ['class' => 'form-select', 'onchange' => 'updateForm();'])];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_TITLE, null) . '<br>' . new Input('title')];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_CODE, null) . '<br>' . new Input('code')];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_SYMBOL_LEFT, null) . '<br>' . new Input('symbol_left')];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_SYMBOL_RIGHT, null) . '<br>' . new Input('symbol_right')];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_DECIMAL_POINT, null) . '<br>' . new Input('decimal_point')];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_THOUSANDS_POINT, null) . '<br>' . new Input('thousands_point')];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_DECIMAL_PLACES, null) . '<br>' . new Input('decimal_places')];
  $contents[] = ['text' => sprintf(TEXT_INFO_CURRENCY_VALUE, null) . '<br>' . new Input('value')];
  $contents[] = [
    'text' => '<div class="form-check form-switch">'
            . new Tickable('default', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'cDefault'], 'checkbox')
            . '<label for="cDefault" class="form-check-label text-muted"><small>' . TEXT_INFO_SET_AS_DEFAULT . '</small></label></div>',
  ];

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['Admin']->link()),
  ];
