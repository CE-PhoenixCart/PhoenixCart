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

  require language::map_to_translation('checkout_confirmation.php');

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
