<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_NEW_ORDERS_STATUS;
  $link = $GLOBALS['link'];

  $contents = ['form' => new Form('status', (clone $link)->set_parameter('action', 'insert'))];
  $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];

  $orders_status_inputs_string = '';
  foreach (language::load_all() as $l) {
    $orders_status_inputs_string .= '<div class="input-group mb-1"><span class="input-group-text">' . $GLOBALS['Admin']->catalog_image('includes/languages/' . $l['directory'] . '/images/' . $l['image'], [], $l['name']) . '</span>' . new Input('orders_status_name[' . $l['id'] . ']', ['id' => "osName-{$l['code']}"]) . '</div>';
  }

  $contents[] = ['text' => TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string];
  $contents[] = ['text' => '<div class="form-check form-switch">' . new Tickable('public_flag', ['value' => '1', 'class' => 'form-check-input', 'id' => 'osPublicFlag'], 'checkbox') . '<label for="osPublicFlag" class="form-check-label text-muted"><small>' . TEXT_SET_PUBLIC_STATUS . '</small></label></div>'];
  $contents[] = ['text' => '<div class="form-check form-switch">' . new Tickable('downloads_flag', ['value' => '1', 'class' => 'form-check-input', 'id' => 'osDownloadsFlag'], 'checkbox') . '<label for="osDownloadsFlag" class="form-check-label text-muted"><small>' . TEXT_SET_DOWNLOADS_STATUS . '</small></label></div>'];
  $contents[] = ['text' => '<div class="form-check form-switch">' . new Tickable('default', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'osDefaultFlag'], 'checkbox') . '<label for="osDefaultFlag" class="form-check-label text-muted"><small>' . TEXT_SET_DEFAULT . '</small></label></div>'];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
