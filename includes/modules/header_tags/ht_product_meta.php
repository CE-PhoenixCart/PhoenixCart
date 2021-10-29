<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_product_meta extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_PRODUCT_META_';

    protected $group = 'header_tags';

    public function execute() {
      global $product;

      if (isset($_GET['products_id'], $product) && !Text::is_empty($product->get('seo_description'))) {
        $GLOBALS['Template']->add_block('<meta name="description" content="' . Text::output($product->get('seo_description')) . '" />' . PHP_EOL, $this->group);
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_PRODUCT_META_STATUS' => [
          'title' => 'Enable Product Meta Module',
          'value' => 'True',
          'desc' => 'Do you want to allow product meta tags to be added to the page header?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_PRODUCT_META_KEYWORDS_STATUS' => [
          'title' => 'Enable Keyword Search Engine',
          'value' => 'True',
          'desc' => 'Keywords can be used as an internal search engine...',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_PRODUCT_META_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
