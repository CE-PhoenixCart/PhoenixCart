<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_manufacturer extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PI_MANUFACTURER_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      $pi_brand = $GLOBALS['product']->get('brand');
      
      $m = [];
      
      if ($pi_brand->_data) {
        $m['brand']['name'] = $pi_brand->getData('manufacturers_name');
        $m['brand']['email'] = $pi_brand->getData('manufacturers_email');
        $m['brand']['address'] = $pi_brand->getData('manufacturers_address');
        $m['brand']['in_eu'] = $pi_brand->getData('in_eu');
        
        $m['importer']['name'] = $pi_brand->getData('importers_name');
        $m['importer']['email'] = $pi_brand->getData('importers_email');
        $m['importer']['address'] = $pi_brand->getData('importers_address');
        
        // remove empty values
        $m = array_map('array_filter', $m);
        $m = array_filter($m);
      
        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';
      }
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
          'value' => '65',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
