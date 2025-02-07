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

  $contents = ['form' => new Form('delete', (clone $GLOBALS['link'])->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_DELETE_INTRO];
  $contents[] = ['class' => 'text-center text-uppercase fw-bold', 'text' => $buInfo->file];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['link']),
  ];
