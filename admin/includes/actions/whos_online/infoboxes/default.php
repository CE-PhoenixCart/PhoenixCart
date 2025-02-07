<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($table_definition['info']->customer_id)) {
    $heading = TABLE_HEADING_SHOPPING_CART;

    if ( $table_definition['info']->customer_id > 0 ) {
      $session_customer_id = $_SESSION['customer_id'] ?? null;
      $session_currency = $_SESSION['currency'] ?? null;
      $_SESSION['customer_id'] = $table_definition['info']->customer_id;
      $_SESSION['currency'] = DEFAULT_CURRENCY;

      $currencies = &Guarantor::ensure_global('currencies');
      $shoppingCart = new shoppingCart();
      $shoppingCart->restore_contents();

      foreach ($shoppingCart->get_products() as $product) {
        $contents[] = ['text' => sprintf(TEXT_SHOPPING_CART_ITEM, $product->get('quantity'), $product->get('name'))];
      }

      $contents[] = [
        'class' => 'table-dark text-end',
        'text' => sprintf(TEXT_SHOPPING_CART_SUBTOTAL, $currencies->format($shoppingCart->show_total())),
      ];

      $_SESSION['customer_id'] = $session_customer_id;
      $_SESSION['currency'] = $session_currency;
    } else {
      $contents[] = ['text' => TEXT_SHOPPING_CART_NA];
    }
  }
