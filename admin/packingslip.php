<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $currencies = new currencies();

  $oID = Text::input($_GET['oID']);

  $order = new order($oID);
  $address = $customer_data->get_module('address');

  require 'includes/segments/process_action.php';
  require 'includes/template_top.php';
  
  if ($view_file = $Admin->locate('/views', $action)) {
    require $view_file;
  }
  
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
  