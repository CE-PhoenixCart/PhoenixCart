<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require language::map_to_translation('specials.php');

  $listing_sql = (new product_searcher([], ['specials' => ['status' => 1]]))->find();

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
