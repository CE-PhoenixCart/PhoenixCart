<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (!isset($GLOBALS['table_definition']['info']->id)) {
    error_log('Nothing selected for editing');
    return;
  }
  
  $aInfo =& $GLOBALS['table_definition']['info'];
  $heading = $aInfo->user_name;

  $link = $GLOBALS['link']->set_parameter('aID', $aInfo->id);

  $contents = ['form' => new Form('administrator', (clone $link)->set_parameter('action', 'save')->set_parameter('autocomplete', 'off'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
  $contents[] = ['text' => TEXT_INFO_USERNAME . (new Input('username', ['value' => $aInfo->user_name, 'autocomplete' => 'off', 'autocapitalize' => 'none']))->require()];
  $contents[] = ['text' => TEXT_INFO_NEW_PASSWORD . (new Input('password', ['autocomplete' => 'off', 'autocapitalize' => 'none'], 'password'))->require()];

  if (is_array($GLOBALS['htpasswd_lines'])) {
    $checkbox = new Tickable('htaccess', ['class' => 'form-check-input', 'id' => 'aHtpasswd', 'value' => 'true'], 'checkbox');

    foreach ($GLOBALS['htpasswd_lines'] as $htpasswd_line) {
      list($ht_username, $ht_password) = explode(':', $htpasswd_line, 2);

      if ($ht_username == $aInfo->user_name) {
        $checkbox->tick();
        break;
      }
    }

    $contents[] = [
      'text' => '<div class="form-check form-switch">' . $checkbox
              . '<label for="aHtpasswd" class="form-check-label text-muted"><small>' . TEXT_INFO_PROTECT_WITH_HTPASSWD . '</small></label></div>',
    ];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . new Button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', [], $GLOBALS['Admin']->link('administrators.php', ['aID' => $aInfo->id])),
  ];
