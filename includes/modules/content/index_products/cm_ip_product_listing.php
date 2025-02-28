<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_ip_product_listing extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_IP_PRODUCT_LISTING_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      if (empty($_GET['manufacturers_id'])) {
// show the products in a given category
        $criteria = [
          'products_to_categories' => ['categories_id' => (int)$GLOBALS['current_category_id']],
        ];

        if (isset($_GET['filter_id']) && !Text::is_empty($_GET['filter_id'])) {
// We are asked to show only a specific manufacturer
          $criteria['manufacturers'] = ['manufacturers_id' => (int)$_GET['filter_id']];
        }
// Otherwise, we show them all
      } else {
// show the products of a specified manufacturer
        $criteria = [
          'manufacturers' => ['manufacturers_id' => (int)$_GET['manufacturers_id']],
        ];

        if (isset($_GET['filter_id']) && !Text::is_empty($_GET['filter_id'])) {
// We are asked to show only a specific category
          $criteria['products_to_categories'] = ['categories_id' => (int)$_GET['filter_id']];
        }
// Otherwise, we show them all
      }

      $listing_sql = (new product_searcher([], $criteria))->find();

      require 'includes/system/segments/sortable_product_columns.php';

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12 mb-4',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
