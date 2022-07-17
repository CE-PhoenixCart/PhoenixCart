<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = $GLOBALS['aInfo']->user_name;

  $contents = ['form' => new Form('administrator', $GLOBALS['Admin']->link('administrators.php', ['aID' => $GLOBALS['aInfo']->id, 'action' => 'delete_confirm']))];
  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
  $contents[] = ['class' => 'text-center text-uppercase font-weight-bold', 'text' => $GLOBALS['aInfo']->user_name];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger mr-2')
            . new Button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', [], $GLOBALS['Admin']->link('administrators.php', ['aID' => $GLOBALS['aInfo']->id])),
  ];
