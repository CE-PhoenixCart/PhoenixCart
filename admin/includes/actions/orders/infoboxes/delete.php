<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_DELETE_ORDER;

  $link = $GLOBALS['Admin']->link()->retain_query_except(['action'])->set_parameter('oID', $table_definition['info']->orders_id);
  $contents = ['form' => new Form('orders', (clone $link)->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO . '<br><br><strong>' . $table_definition['info']->customers_name . '</strong>'];
  $contents[] = [
    'text' => '<div class="custom-control custom-switch py-2">'
            . new Tickable('restock', ['value' => 'on', 'class' => 'custom-control-input', 'id' => 'oRestock'], 'checkbox')
            . '<label for="oRestock" class="custom-control-label text-muted">' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY . '</label></div>',
  ];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger mr-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $link),
  ];
