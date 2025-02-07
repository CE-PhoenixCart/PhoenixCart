<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['table_definition']['info']->languages_id)) {
    $lInfo = &$GLOBALS['table_definition']['info'];
    $heading = $lInfo->name;
    $link = $GLOBALS['link']->set_parameter('lID', (int)$GLOBALS['table_definition']['info']->languages_id);

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', (clone $link)->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_DIRECTORY, DIR_WS_CATALOG . 'includes/languages/', $lInfo->directory)];
    $contents[] = ['text' => sprintf(TEXT_INFO_LANGUAGE_SORT_ORDER, $lInfo->sort_order)];
  }
