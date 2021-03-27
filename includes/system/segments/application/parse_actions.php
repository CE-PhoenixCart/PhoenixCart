<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

// Shopping cart actions
  if (isset($_GET['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if (!$session_started) {
      Guarantor::ensure_global('Linker')->build('cookie_usage.php')->redirect();
    }

    if ('true' === DISPLAY_CART) {
      $goto = 'shopping_cart.php';
      $parameters = ['action', 'cPath', 'products_id', 'pid'];
    } else {
      $goto = $PHP_SELF;
      if ('buy_now' === $_GET['action']) {
        $parameters = ['action', 'pid', 'products_id'];
      } else {
        $parameters = ['action', 'pid'];
      }
    }

    Actions::parse($_GET['action']);
  }
