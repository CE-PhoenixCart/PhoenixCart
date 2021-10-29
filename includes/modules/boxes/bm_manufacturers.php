<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class bm_manufacturers extends abstract_block_module {

    const CONFIG_KEY_BASE = 'MODULE_BOXES_MANUFACTURERS_';

    public function execute() {
      $manufacturers_query = $GLOBALS['db']->query("SELECT manufacturers_id AS id, manufacturers_name AS text FROM manufacturers ORDER BY manufacturers_name");
      if ($number_of_rows = mysqli_num_rows($manufacturers_query)) {
        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/block_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_BOXES_MANUFACTURERS_STATUS' => [
          'title' => 'Enable Manufacturers Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_BOXES_MANUFACTURERS_CONTENT_PLACEMENT' => [
          'title' => 'Content Placement',
          'value' => 'Left Column',
          'desc' => 'Should the module be loaded in the left or right column?',
          'set_func' => "Config::select_one(['Left Column', 'Right Column'], ",
        ],
        'MODULE_BOXES_MANUFACTURERS_MAX_LIST' => [
          'title' => 'Manufacturers List',
          'value' => '9',
          'desc' => 'When the number of manufacturers exceeds this number, a drop-down list will be displayed instead of the default list',
        ],
        'MODULE_BOXES_MANUFACTURERS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }
  }
