<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info'])) {
    $link = $GLOBALS['Admin']->link('customers.php')->retain_query_except(['action'])->set_parameter('cID', (int)$table_definition['info']->id);
    $heading = $table_definition['info']->name;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning me-2', (clone $link)->set_parameter('action', 'edit'))
              . $GLOBALS['Admin']->button(IMAGE_DELETE, 'fas fa-trash', 'btn-danger me-2', $link->set_parameter('action', 'confirm'))
              . $GLOBALS['Admin']->button(IMAGE_ORDERS, 'fas fa-shopping-cart', 'btn-info me-2', $GLOBALS['Admin']->link('orders.php', ['cID' => $table_definition['info']->id]))
              . $GLOBALS['Admin']->button(IMAGE_EMAIL, 'fas fa-at', 'btn-info', $GLOBALS['Admin']->link('mail.php', ['customer' => $table_definition['info']->email_address])),
    ];
    $contents[] = ['text' => sprintf(TEXT_DATE_ACCOUNT_CREATED, Date::abridge($table_definition['info']->date_account_created))];
    $contents[] = ['text' => sprintf(TEXT_DATE_ACCOUNT_LAST_MODIFIED, Date::abridge($table_definition['info']->date_account_last_modified))];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE_LAST_LOGON, Date::abridge($table_definition['info']->date_last_logon))];
    $contents[] = ['text' => sprintf(TEXT_INFO_NUMBER_OF_LOGONS, $table_definition['info']->number_of_logons)];

    if (!empty($table_definition['info']->country_name)) {
      $contents[] = ['text' => sprintf(TEXT_INFO_COUNTRY, $table_definition['info']->country_name)];
    }

    $contents[] = ['text' => sprintf(TEXT_INFO_NUMBER_OF_REVIEWS, $table_definition['info']->number_of_reviews)];
  }
