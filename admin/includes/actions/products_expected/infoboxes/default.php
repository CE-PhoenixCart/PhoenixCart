<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']) && is_object($table_definition['info'])) {
    $heading = $table_definition['info']->products_name;

    $contents[] = [
      'class' => 'text-center',
      'text' => $GLOBALS['Admin']->button(IMAGE_EDIT, 'fas fa-cogs', 'btn-warning', $GLOBALS['Admin']->link('catalog.php', ['pID' => (int)$table_definition['info']->products_id, 'action' => 'new_product'])),
    ];
    $contents[] = ['text' => sprintf(TEXT_INFO_DATE_EXPECTED, Date::abridge($table_definition['info']->products_date_available))];
  }
