<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $oInfo = &$table_definition['info'];
  $heading = TEXT_INFO_HEADING_EDIT_ORDERS_STATUS;
  $link = $GLOBALS['link']->set_parameter('oID', $oInfo->orders_status_id);

  $contents = ['form' => new Form('status', (clone $link)->set_parameter('action', 'save'))];
  $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];

  $orders_status_inputs_string = '';
  foreach (language::load_all() as $l) {
    $orders_status_inputs_string .= '<div class="input-group mb-1"><span class="input-group-text">' . $GLOBALS['Admin']->catalog_image('includes/languages/' . $l['directory'] . '/images/' . $l['image'], [], $l['name']) . '</span>' . new Input('orders_status_name[' . $l['id'] . ']', ['id' => "osName-{$l['code']}", 'value' => order_status::fetch_name($oInfo->orders_status_id, $l['id'])]) . '</div>';
  }

  $contents[] = ['text' => TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string];
  $contents[] = ['text' => '<div class="form-check form-switch">' . (new Tickable('public_flag', ['value' => '1', 'class' => 'form-check-input', 'id' => 'osPublicFlag'], 'checkbox'))->tick('1' == $oInfo->public_flag) . '<label for="osPublicFlag" class="form-check-label text-muted"><small>' . TEXT_SET_PUBLIC_STATUS . '</small></label></div>'];
  $contents[] = ['text' => '<div class="form-check form-switch">' . (new Tickable('downloads_flag', ['value' => '1', 'class' => 'form-check-input', 'id' => 'osDownloadsFlag'], 'checkbox'))->tick('1' == $oInfo->downloads_flag) . '<label for="osDownloadsFlag" class="form-check-label text-muted"><small>' . TEXT_SET_DOWNLOADS_STATUS . '</small></label></div>'];
  if (DEFAULT_ORDERS_STATUS_ID != $oInfo->orders_status_id) {
    $contents[] = ['text' => '<div class="form-check form-switch">' . new Tickable('default', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'osDefaultFlag'], 'checkbox') . '<label for="osDefaultFlag" class="form-check-label text-muted"><small>' . TEXT_SET_DEFAULT . '</small></label></div>'];
  }
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
