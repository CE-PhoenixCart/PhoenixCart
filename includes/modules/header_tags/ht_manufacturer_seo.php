<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_manufacturer_seo extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_MANUFACTURERS_SEO_';

    protected $group = 'header_tags';

    public function execute() {
      if ((basename(Request::get_page()) === 'index.php') && isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) {
        $brand_seo_description = $GLOBALS['brand']->getData('manufacturers_seo_description');

        if (!Text::is_empty($brand_seo_description)) {
          $GLOBALS['Template']->add_block('<meta name="description" content="' . Text::output($brand_seo_description) . '" />' . PHP_EOL, $this->group);
        }
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_MANUFACTURERS_SEO_STATUS' => [
          'title' => 'Enable Manufacturer Meta Module',
          'value' => 'True',
          'desc' => 'Do you want to allow Manufacturer meta tags to be added to the page header?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_MANUFACTURERS_SEO_DESCRIPTION_STATUS' => [
          'title' => 'Display Manufacturer Meta Description',
          'value' => 'True',
          'desc' => 'Manufacturer Descriptions help your site and your sites visitors.',
          'set_func' => "Config::select_one(['True'], ",
        ],
        'MODULE_HEADER_TAGS_MANUFACTURERS_SEO_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '110',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
