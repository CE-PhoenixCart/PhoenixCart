<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $lInfo = &$GLOBALS['table_definition']['info'];
  $heading = TEXT_INFO_HEADING_EDIT_LANGUAGE;
  $link = $GLOBALS['link']->set_parameter('lID', (int)$lInfo->languages_id);

  $contents = ['form' => new Form('languages', (clone $link)->set_parameter('action', 'save'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_NAME, null) . '<br>' . new Input('name', ['autocomplete' => 'off', 'value' => $lInfo->name])];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_CODE, null) . '<br>' . new Input('code', ['value' => $lInfo->code])];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_IMAGE, null) . '<br>' . new Input('image', ['value' => $lInfo->image])];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_DIRECTORY, null, null) . '<br>' . new Input('directory', ['value' => $lInfo->directory])];
  $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_SORT_ORDER, null) . '<br>' . new Input('sort_order', ['value' => $lInfo->sort_order])];
  if (DEFAULT_LANGUAGE != $lInfo->code) {
    $contents[] = [
      'text' => '<div class="form-check form-switch">'
              . new Tickable('default', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'lDefault'], 'checkbox')
              . '<label for="lDefault" class="form-check-label text-muted"><small>' . TEXT_SET_DEFAULT . '</small></label></div>',
    ];
  }
  $contents[] = [
    'class' => 'text-center',
    'text' => '<br>' . new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
