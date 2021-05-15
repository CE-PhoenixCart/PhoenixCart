<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (!isset($default_column)) {
    $default_column = 'PRODUCT_LIST_NAME';
  }

  class_exists('splitPageResults');
  $column_specifications = array_filter([
    'PRODUCT_LIST_MODEL' => [
      'order_by' => " ORDER BY p.products_model%s, pd.products_name",
      'heading' => TABLE_HEADING_MODEL,
      'sortable' => true,
    ],
    'PRODUCT_LIST_NAME' => [
      'order_by' => " ORDER BY pd.products_name%s",
      'heading' => TABLE_HEADING_PRODUCTS,
      'sortable' => true,
    ],
    'PRODUCT_LIST_MANUFACTURER' => [
      'order_by' => " ORDER BY m.manufacturers_name%s, pd.products_name",
      'heading' => TABLE_HEADING_MANUFACTURER,
      'sortable' => true,
    ],
    'PRODUCT_LIST_QUANTITY' => [
      'order_by' => " ORDER BY p.products_quantity%s, pd.products_name",
      'heading' => TABLE_HEADING_QUANTITY,
      'sortable' => true,
    ],
    'PRODUCT_LIST_IMAGE' => [
      'order_by' => " ORDER BY pd.products_name",
      'heading' => TABLE_HEADING_IMAGE,
      'sortable' => false,
    ],
    'PRODUCT_LIST_WEIGHT' => [
      'order_by' => " ORDER BY p.products_weight%s, pd.products_name",
      'heading' => TABLE_HEADING_WEIGHT,
      'sortable' => true,
    ],
    'PRODUCT_LIST_PRICE' => [
      'order_by' => " ORDER BY final_price%s, pd.products_name",
      'heading' => TABLE_HEADING_PRICE,
      'sortable' => true,
    ],
    'PRODUCT_LIST_ID' => [
      'order_by' => " ORDER BY p.products_id%s, pd.products_name",
      'heading' => TABLE_HEADING_LATEST_ADDED,
      'sortable' => true,
    ],
    'PRODUCT_LIST_ORDERED' => [
      'order_by' => " ORDER BY p.products_ordered%s, pd.products_name",
      'heading' => TABLE_HEADING_ORDERED,
      'sortable' => true,
    ],
  ], function ($k) use ($default_column) {
    return ((constant($k) > 0) || ($k === $default_column));
  }, ARRAY_FILTER_USE_KEY);

  uksort($column_specifications, function ($a, $b) {
    return (constant($a) <=> constant($b));
  });

  $num_list = (isset($_GET['view']) && ($_GET['view'] === 'all') ) ? 999999 : MAX_DISPLAY_SEARCH_RESULTS;
  $parameters = [
    'column_specifications' => &$column_specifications,
    'default_column' => &$default_column,
    'direction' => &$direction,
    'listing_sql' => &$listing_sql,
    'num_list' => &$num_list,
    'sort_order' => &$sort_order,
  ];
  $GLOBALS['hooks']->register_pipeline('filter', $parameters);
  $column_list = array_keys($column_specifications);

  if ( (isset($_GET['sort'])) && (preg_match('{\A[1-9]\d*[ad]\z}', $_GET['sort'])) && (substr($_GET['sort'], 0, -1) <= count($column_list)) ) {
    $sort_column = (int)(substr($_GET['sort'], 0 , -1)) - 1;
  } else {
    $sort_column = array_search($default_column, $column_list, true);
    if (false === $sort_column) {
      $sort_column = 0;
      error_log("Can't find default sort column:  [$default_column]");
    }

    $_GET['sort'] = ($sort_column + 1) . ($sort_order ?? 'a');
  }

  $direction = ('d' === substr($_GET['sort'], -1)) ? ' DESC' : '';

  if (isset($column_specifications[$column_list[$sort_column]]['order_by'])) {
    $listing_sql .= sprintf($column_specifications[$column_list[$sort_column]]['order_by'], $direction);
  }
