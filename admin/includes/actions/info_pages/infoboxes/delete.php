<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $pInfo = $table_definition['info'];
  $heading = sprintf(TEXT_HEADING_DELETE_PAGE, $pInfo->pages_title);
  $link = $GLOBALS['link']->set_parameter('pID', (int)$pInfo->pages_id);

  $contents = ['form' => new Form('pages', (clone $link)->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_DELETE_PAGE_INTRO];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
