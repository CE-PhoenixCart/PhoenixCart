<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_manufacturer_title extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $Template, $brand;

      if ( (basename(Request::get_page()) === 'index.php')
        && isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id']))
      {
        if ( (MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SEO_TITLE_OVERRIDE !== 'True')
           || Text::is_empty($brand_title = $brand->getData('manufacturers_seo_title')) )
        {
          $brand_title = $brand->getData('manufacturers_name');
        }

        $Template->set_title($brand_title . MODULE_HEADER_TAGS_MANUFACTURER_SEO_SEPARATOR . $Template->get_title());
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Manufacturer Title Module',
          'value' => 'True',
          'desc' => 'Do you want to allow manufacturer titles to be added to the page title?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
        $this->config_key_base . 'SEO_TITLE_OVERRIDE' => [
          'title' => 'SEO Title Override?',
          'value' => 'True',
          'desc' => 'Do you want to allow manufacturer names to be over-ridden by your SEO Titles (if set)?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
      ];
    }

  }
