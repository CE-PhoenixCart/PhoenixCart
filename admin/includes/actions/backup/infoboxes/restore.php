<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $link = $GLOBALS['link']->set_parameter('file', $buInfo->file);
  $heading = $buInfo->date;

  $contents = ['form' => new Form('restore', (clone $GLOBALS['link'])->set_parameter('action', 'restore_now'))];
  $contents[] = [
    'class' => 'text-break',
    'text' => sprintf(TEXT_INFO_RESTORE,
                DIR_FS_BACKUP . (($buInfo->compression == TEXT_NO_EXTENSION) ? $buInfo->file : pathinfo($buInfo->file, PATHINFO_FILENAME)),
                ($buInfo->compression == TEXT_NO_EXTENSION) ? '' : TEXT_INFO_UNPACK)];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_RESTORE, 'fas fa-file-upload', 'btn-warning me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
