<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_in_category_listing extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_IN_CATEGORY_LISTING_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      $category_name = $GLOBALS['category_tree']->get($GLOBALS['current_category_id'], 'name');

      $display_tree = new tree_display($GLOBALS['category_tree']);
      $display_tree->setMaximumLevel(1);
      $categories = $display_tree->buildBranchArray($GLOBALS['current_category_id']);

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
        $this->config_key_base . 'DISPLAY_ROW' => [
          'title' => 'Categories Per Row',
          'value' => 'row row-cols-2 row-cols-sm-3 row-cols-md-4',
          'desc' => 'How many categories should display per Row per viewport?  Default:  XS 2, SM 3, MD and above 4',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
