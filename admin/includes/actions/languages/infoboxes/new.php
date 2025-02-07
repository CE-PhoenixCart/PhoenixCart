<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_NEW_LANGUAGE;

  $contents = ['form' => new Form('languages', $GLOBALS['Admin']->link()->set_parameter('action', 'insert'))];
  $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_NAME, null) . '<br>' . new Input('name', ['autocomplete' => 'off'])];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_CODE, null) . '<br>' . new Input('code')];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_IMAGE, null) . '<br>' . new Input('image', ['value' => 'icon.gif'])];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_DIRECTORY, null, null) . '<br>' . new Input('directory')];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_SORT_ORDER, null) . '<br>' . new Input('sort_order')];
  $contents[] = [
    'text' => '<div class="form-check form-switch">'
            . new Tickable('default', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'lDefault'], 'checkbox')
            . '<label for="lDefault" class="form-check-label text-muted"><small>' . TEXT_SET_DEFAULT . '</small></label></div>',
  ];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
