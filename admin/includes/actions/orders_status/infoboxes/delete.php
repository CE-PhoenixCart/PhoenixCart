<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $oInfo = &$table_definition['info'];
  $heading = TEXT_INFO_HEADING_DELETE_ORDERS_STATUS;
  $link = $GLOBALS['link']->set_parameter('oID', $oInfo->orders_status_id);

  $contents = ['form' => new Form('status', (clone $link)->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
  $contents[] = ['class' => 'text-center text-uppercase fw-bold', 'text' => $oInfo->orders_status_name];
  if ($GLOBALS['remove_status']) {
    $contents[] = [
      'class' => 'text-center',
      'text' => '<br>'
              . new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
              . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
    ];
  }
