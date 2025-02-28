<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $mInfo = &$table_definition['info'];
  $heading = TEXT_HEADING_DELETE_MANUFACTURER;
  $link = $GLOBALS['link']->set_parameter('mID', (int)$mInfo->manufacturers_id);

  $contents = ['form' => new Form('manufacturers', (clone $link)->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_DELETE_INTRO];
  $contents[] = ['text' => '<strong>' . $mInfo->manufacturers_name . '</strong>'];
  $contents[] = ['text' => '<div class="form-check form-switch">' . new Tickable('delete_image', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'mDeleteImg'], 'checkbox') . '<label for="mDeleteImg" class="form-check-label text-muted"><small>' . TEXT_DELETE_IMAGE . '</small></label></div>'];

  if ($mInfo->products_count > 0) {
    $contents[] = ['text' => '<div class="form-check form-switch">' . new Tickable('delete_products', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'mDeleteProducts'], 'checkbox') . '<label for="mDeleteProducts" class="form-check-label text-muted"><small>' . TEXT_DELETE_PRODUCTS . '</small></label></div>'];
    $contents[] = ['text' => sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count)];
  }

  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
