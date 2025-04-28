<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

const TABLE_HEADING_NEW_PRODUCTS = 'New Products For %s';

const TEXT_NO_PRODUCTS = 'There are no products available in this category.';
const TEXT_NUMBER_OF_PRODUCTS = 'Number of Products: ';
const TEXT_SHOW = '<strong>Show:</strong>';
const TEXT_BUY = 'Buy 1 \'';
const TEXT_NOW = '\' now';
const TEXT_ALL_CATEGORIES = 'All Categories';
const TEXT_ALL_MANUFACTURERS = 'All Manufacturers';

// seo
if ( ($category_depth == 'top') && (!isset($_GET['manufacturers_id'])) ) {
  const META_SEO_TITLE = 'Welcome to Our Online Store';
  const META_SEO_DESCRIPTION = 'Discover a wide selection of products and enjoy easy online shopping with great customer service.';
}

