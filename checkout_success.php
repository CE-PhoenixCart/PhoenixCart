<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

// if the customer is not logged on, redirect them to the shopping cart page
  if (!isset($_SESSION['customer_id'])) {
    Href::redirect($Linker->build('shopping_cart.php'));
  }

  $orders_query = $db->query("SELECT orders_id FROM orders WHERE customers_id = " . (int)$_SESSION['customer_id'] . " ORDER BY date_purchased DESC LIMIT 1");

// redirect to shopping cart page if no orders exist
  if ( !mysqli_num_rows($orders_query) ) {
    Href::redirect($Linker->build('shopping_cart.php'));
  }

  $orders = $orders_query->fetch_assoc();

  $order_id = $orders['orders_id'];

  if ( isset($_GET['action']) && ($_GET['action'] === 'update') ) {
    Href::redirect($Linker->build('index.php'));
  }

  require language::map_to_translation('checkout_success.php');

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
