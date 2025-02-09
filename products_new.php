<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require language::map_to_translation('products_new.php');

  $listing_sql = (new product_searcher([], []))->find();

  $default_column = 'PRODUCT_LIST_ID';
  $sort_order = 'd';

  require $Template->map(__FILE__, 'page');

  require 'includes/application_bottom.php';
