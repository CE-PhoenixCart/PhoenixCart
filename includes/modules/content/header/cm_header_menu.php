<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_header_menu extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_HEADER_MENU_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      $category_tree = &Guarantor::ensure_global('category_tree');

      $menu_array = [MODULE_CONTENT_HEADER_MENU_STYLE, MODULE_CONTENT_HEADER_MENU_COLLAPSE];
      $menu_style = implode(' ', $menu_array);

      $tpl_data = ['group' => $this->group, 'file' => __FILE__];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Horizontal Menu Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12 mb-1',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'STYLE' => [
          'title' => 'Colour Scheme',
          'value' => '',
          'desc' => 'What colour scheme should this Navigation Bar have?  See https://getbootstrap.com/docs/5.3/components/navbar/#color-schemes'
        ],
        $this->config_key_base . 'COLLAPSE' => [
          'title' => 'Collapse Breakpoint',
          'value' => 'navbar-expand-sm',
          'desc' => 'When should this Navigation Bar Show? See https://getbootstrap.com/docs/5.3/components/navbar/#how-it-works',
          'set_func' => "Config::select_one(['navbar-expand', 'navbar-expand-sm', 'navbar-expand-md', 'navbar-expand-lg', 'navbar-expand-xl', 'navbar-expand-xxl'], ",
        ],
        $this->config_key_base . 'MANUFACTURERS' => [
          'title' => 'Display Manufacturers',
          'value' => 'True',
          'desc' => 'Manufacturers will display on the Right Hand Side of the Horizontal Menu, if True',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '900',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
