<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  if (!isset($_GET['products_id'])) {
    Href::redirect($Linker->build('index.php'));
  }

  require language::map_to_translation('product_info.php');

  $product_info_query = $db->query("SELECT p.*, pd.* FROM products p, products_description pd where p.products_status = 1 and p.products_id = " . (int)$_GET['products_id'] . " and pd.products_id = p.products_id and pd.language_id = " . (int)$_SESSION['languages_id']);
  if ($product_info = $product_info_query->fetch_assoc()) {
    require $Template->map(__FILE__, 'page');
  } else {
    require $Template->map('product_info_not_found.php', 'page');
  }

  require 'includes/application_bottom.php';
