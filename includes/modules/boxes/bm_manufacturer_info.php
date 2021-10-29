<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class bm_manufacturer_info extends abstract_block_module {

    const CONFIG_KEY_BASE = 'MODULE_BOXES_MANUFACTURER_INFO_';

    public function execute() {
      if (isset($GLOBALS['product']) && ($GLOBALS['product'] instanceof Product)) {
        $bm_brand = $GLOBALS['product']->get('brand');

        if ($bm_brand->_data) {
          $_brand = $bm_brand->getData('manufacturers_name');
          $_image = $bm_brand->getData('manufacturers_image');
          $_url   = $bm_brand->getData('manufacturers_url');
          $_id    = $bm_brand->getData('manufacturers_id');

          $box_title = '<a href="' . $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => (int)$_id]) . '">' . $_brand . '</a>';

          $tpl_data = ['group' => $this->group, 'file' => __FILE__];
          include 'includes/modules/block_template.php';
        }
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_BOXES_MANUFACTURER_INFO_STATUS' => [
          'title' => 'Enable Manufacturer Info Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_BOXES_MANUFACTURER_INFO_CONTENT_PLACEMENT' => [
          'title' => 'Content Placement',
          'value' => 'Right Column',
          'desc' => 'Should the module be loaded in the left or right column?',
          'set_func' => "Config::select_one(['Left Column', 'Right Column'], ",
        ],
        'MODULE_BOXES_MANUFACTURER_INFO_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
