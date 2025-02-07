<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_NEW_ADMINISTRATOR;

  $contents = ['form' => new Form('administrator', $GLOBALS['Admin']->link('administrators.php', ['action' => 'insert']), 'post', ['autocomplete' => 'off'])];
  $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
  $contents[] = ['text' => TEXT_INFO_USERNAME . (new Input('username', ['autocomplete' => 'off', 'autocapitalize' => 'none']))->require()];
  $contents[] = ['text' => TEXT_INFO_PASSWORD . (new Input('password', ['autocomplete' => 'off', 'autocapitalize' => 'none'], 'password'))->require()];

  if (is_array($GLOBALS['htpasswd_lines'])) {
    $contents[] = [
      'text' => '<div class="form-check form-switch">'
              . new Tickable('htaccess', ['class' => 'form-check-input', 'id' => 'aHtpasswd', 'value' => 'true'], 'checkbox')
              . '<label for="aHtpasswd" class="form-check-label text-muted"><small>' . TEXT_INFO_PROTECT_WITH_HTPASSWD . '</small></label></div>'];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . new Button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', [], $GLOBALS['Admin']->link('administrators.php')),
  ];
