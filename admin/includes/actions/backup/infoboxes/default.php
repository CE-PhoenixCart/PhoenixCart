<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($GLOBALS['buInfo']->file)) {
    $buInfo = $GLOBALS['buInfo'];
    $link = $GLOBALS['link']->set_parameter('file', $buInfo->file);
    $heading = $buInfo->date;

    $buttons = $GLOBALS['Admin']->button(IMAGE_RESTORE, 'fas fa-file-upload', 'btn-warning me-2', (clone $GLOBALS['link'])->set_parameter('action', 'restore'));
    if ($GLOBALS['dir_ok']) {
      $buttons .= $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', $GLOBALS['link']->set_parameter('action', 'delete'));
    }

    $contents[] = ['class' => 'text-center', 'text' => $buttons];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE, $buInfo->date)];
    $contents[] = ['text' => sprintf(TEXT_INFO_SIZE, $buInfo->size)];
    $contents[] = ['text' => sprintf(TEXT_INFO_COMPRESSION, $buInfo->compression)];
  }
