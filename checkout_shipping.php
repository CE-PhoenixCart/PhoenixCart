<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require 'includes/system/segments/checkout/pipeline.php';

  $total_weight = $_SESSION['cart']->show_weight();
  $total_count = $_SESSION['cart']->count_contents();

// load all enabled shipping modules
  $shipping_modules = new shipping();

  $free_shipping = ot_shipping::is_eligible_free_shipping($order->delivery['country_id'], $order->info['total'] - $order->info['shipping_cost']);

  $module_count = $shipping_modules->count();
// process the selected shipping method
  if (Form::validate_action_is('process')) {
    $shipping_modules->process_selection();
  }

// get all available shipping quotes
  $quotes = $shipping_modules->quote();

  shipping::ensure_enabled();

  require language::map_to_translation('checkout_shipping.php');

  if ( defined('SHIPPING_ALLOW_UNDEFINED_ZONES') && (SHIPPING_ALLOW_UNDEFINED_ZONES === 'False') && ($module_count <= 0) ) {
    $messageStack->add_session('checkout_address', ERROR_NO_SHIPPING_AVAILABLE_TO_SHIPPING_ADDRESS);
    Href::redirect($Linker->build('checkout_shipping_address.php'));
  }

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
