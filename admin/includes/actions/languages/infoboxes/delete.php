<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $lInfo = &$GLOBALS['table_definition']['info'];
  $heading = TEXT_INFO_HEADING_DELETE_LANGUAGE;
  $link = $GLOBALS['link']->set_parameter('lID', (int)$lInfo->languages_id);

  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
  $contents[] = [
    'class' => 'text-center text-uppercase font-weight-bold',
    'text' => $lInfo->name,
  ];
  $contents[] = [
    'class' => 'text-center',
    'text' => (($remove_language) ? $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger mr-2', (clone $link)->set_parameter('action', 'delete_confirm')) : '')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
