<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']->newsletters_id)) {
    $nInfo = &$table_definition['info'];
    $heading = $nInfo->title;
    $link = $GLOBALS['link']->set_parameter('nID', (int)$nInfo->newsletters_id);

    if ($nInfo->locked > 0) {
      $contents[] = [
        'class' => 'text-center',
        'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'new'))
                . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', (clone $link)->set_parameter('action', 'delete')),
      ];
      $contents[] = [
        'class' => 'text-center',
        'text' => $GLOBALS['Admin']->button(IMAGE_PREVIEW, 'fas fa-eye', 'btn-light me-2', (clone $link)->set_parameter('action', 'preview'))
                . $GLOBALS['Admin']->button(IMAGE_UNLOCK, 'fas fa-lock-open', 'btn-warning', (clone $link)->set_parameter('action', 'unlock')->set_parameter('formid', $_SESSION['sessiontoken'])),
      ];
      $contents[] = ['class' => 'd-grid', 'text' => $GLOBALS['Admin']->button(IMAGE_SEND, 'fas fa-paper-plane', 'btn-success', $link->set_parameter('action', 'send'))];
    } else {
      $contents[] = [
        'class' => 'text-center',
        'text' => $GLOBALS['Admin']->button(IMAGE_PREVIEW, 'fas fa-eye', 'bt-info me-2', (clone $link)->set_parameter('action', 'preview'))
                . $GLOBALS['Admin']->button(IMAGE_LOCK, 'fas fa-lock', 'btn-warning', $link->set_parameter('action', 'lock')->set_parameter('formid', $_SESSION['sessiontoken'])),
      ];
    }

    $contents[] = ['text' => sprintf(TEXT_NEWSLETTER_DATE_ADDED, Date::abridge($nInfo->date_added))];
    if ($nInfo->status == '1') {
      $contents[] = ['text' => sprintf(TEXT_NEWSLETTER_DATE_SENT, Date::abridge($nInfo->date_sent))];
    }
  }
