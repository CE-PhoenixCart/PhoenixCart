<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = $GLOBALS['aInfo']->user_name;

  $contents = ['form' => new Form('administrator', $GLOBALS['Admin']->link('administrators.php', ['aID' => $GLOBALS['aInfo']->id, 'action' => 'save']), 'post', ['autocomplete' => 'off'])];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
  $contents[] = ['text' => TEXT_INFO_USERNAME . (new Input('username', ['value' => $GLOBALS['aInfo']->user_name, 'autocapitalize' => 'none']))->require()];
  $contents[] = ['text' => TEXT_INFO_NEW_PASSWORD . (new Input('password', ['autocapitalize' => 'none'], 'password'))->require()];

  if (is_array($GLOBALS['htpasswd_lines'])) {
    $checkbox = new Tickable('htaccess', ['class' => 'custom-control-input', 'id' => 'aHtpasswd', 'value' => 'true'], 'checkbox');

    foreach ($GLOBALS['htpasswd_lines'] as $htpasswd_line) {
      list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

      if ($ht_username == $GLOBALS['aInfo']->user_name) {
        $checkbox->tick();
        break;
      }
    }

    $contents[] = [
      'text' => '<div class="custom-control custom-switch">' . $checkbox
              . '<label for="aHtpasswd" class="custom-control-label text-muted"><small>' . TEXT_INFO_PROTECT_WITH_HTPASSWD . '</small></label></div>',
    ];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success mr-2')
            . new Button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', [], $GLOBALS['Admin']->link('administrators.php', ['aID' => $GLOBALS['aInfo']->id])),
  ];
