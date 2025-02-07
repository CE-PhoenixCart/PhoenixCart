<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (!is_object($GLOBALS['table_definition']['info'] ?? null)) {
    error_log('Nothing selected for deletion');
    return;
  }

  $aInfo =& $GLOBALS['table_definition']['info'];
  $heading = $aInfo->user_name;

  $link = $GLOBALS['link']->set_parameter('aID', $aInfo->id);

  $contents = ['form' => new Form('administrators', (clone $link)->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
  $contents[] = ['class' => 'text-center text-uppercase fw-bold', 'text' => $aInfo->user_name];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
