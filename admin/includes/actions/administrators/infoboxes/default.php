<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->id)) {
    $heading = $GLOBALS['table_definition']['info']->user_name;

    $contents[] = [
      'class' => 'text-center',
      'text' => new Button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', [], $GLOBALS['Admin']->link('administrators.php', ['aID' => $GLOBALS['table_definition']['info']->id, 'action' => 'edit']))
              . new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', [], $GLOBALS['Admin']->link('administrators.php', ['aID' => $GLOBALS['table_definition']['info']->id, 'action' => 'delete'])),
    ];
  }
