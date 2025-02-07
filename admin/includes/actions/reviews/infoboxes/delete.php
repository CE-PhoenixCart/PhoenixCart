<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $rInfo = &$GLOBALS['table_definition']['info'];
  $heading = TEXT_INFO_HEADING_DELETE_REVIEW;
  $link = (clone $GLOBALS['link'])->set_parameter('rID', (int)$rInfo->reviews_id);

  $contents = ['form' => new Form('reviews', (clone $link)->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_REVIEW_INTRO];
  $contents[] = ['class' => 'text-center text-uppercase fw-bold', 'text' => $rInfo->products_name];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
