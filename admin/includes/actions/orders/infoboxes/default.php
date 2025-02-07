<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (($table_definition['info'] ?? null) instanceof objectInfo) {
    $oInfo = $table_definition['info'];
    $heading = '[' . $oInfo->orders_id . '] ' . $oInfo->date_purchased;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $oInfo->link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger', (clone $oInfo->link)->set_parameter('action', 'delete')),
    ];
    $contents[] = ['text' => sprintf(TEXT_DATE_ORDER_CREATED, $oInfo->date_purchased)];
    if (!Text::is_empty($oInfo->last_modified)) {
      $contents[] = ['text' => sprintf(TEXT_DATE_ORDER_LAST_MODIFIED, $oInfo->last_modified)];
    }
    $contents[] = ['text' => sprintf(TEXT_INFO_PAYMENT_METHOD, $oInfo->payment_method)];
    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_ORDERS_INVOICE, 'fas fa-file-invoice-dollar', 'btn-info me-2', $GLOBALS['Admin']->link('invoice.php')->set_parameter('oID', $oInfo->orders_id), ['newwindow' => true])
              . $GLOBALS['Admin']->button(IMAGE_ORDERS_PACKINGSLIP, 'fas fa-file-contract', 'btn-info', $GLOBALS['Admin']->link('packingslip.php')->set_parameter('oID', $oInfo->orders_id), ['newwindow' => true]),
    ];
  }
