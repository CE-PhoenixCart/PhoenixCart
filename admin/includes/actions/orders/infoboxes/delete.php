<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $heading = TEXT_INFO_HEADING_DELETE_ORDER;

  $contents = ['form' => new Form('orders', $GLOBALS['Admin']->link('orders.php')->set_parameter('oID', $oInfo->orders_id)->set_parameter('action', 'delete_confirm'))];
  $contents[] = ['text' => TEXT_INFO_DELETE_INTRO . '<br><br><strong>' . $oInfo->customers_name . '</strong>'];
  $contents[] = [
    'text' => '<div class="custom-control custom-switch py-2">'
            . new Tickable('restock', ['value' => 'on', 'class' => 'custom-control-input', 'id' => 'oRestock'], 'checkbox')
            . '<label for="oRestock" class="custom-control-label text-muted">' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY . '</label></div>',
  ];
  $contents[] = [
    'class' => 'text-center',
    'text' => new Button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger mr-2')
            . $GLOBALS['Admin']->button(IMAGE_CANCEL, 'fas fa-times', 'btn-light', $GLOBALS['Admin']->link('orders.php')->retain_query_except(['action'])->set_parameter('oID', $oInfo->orders_id)),
  ];
