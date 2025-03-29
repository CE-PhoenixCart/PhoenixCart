<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_footer_extra_icons extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_FOOTER_EXTRA_ICONS_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      if ( defined('MODULE_CONTENT_FOOTER_EXTRA_ICONS_TEXT') && !Text::is_empty(MODULE_CONTENT_FOOTER_EXTRA_ICONS_TEXT)) {
        $brand_icons = MODULE_CONTENT_FOOTER_EXTRA_ICONS_TEXT;
      } else {
        $brand_icons = explode(',', MODULE_CONTENT_FOOTER_EXTRA_ICONS_DISPLAY);
        if (empty($brand_icons)) {
          return;
        }
      }

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
          'value' => 'col-sm-6 text-center text-sm-end',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'DISPLAY' => [
          'title' => 'Icons',
          'value' => 'fab fa-paypal fa-lg,fab fa-cc-visa fa-lg',
          'desc' => 'Icons to display.',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '20',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }
  }
