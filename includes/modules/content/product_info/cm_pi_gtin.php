<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_gtin extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PRODUCT_INFO_GTIN_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      $gtin = $GLOBALS['product']->get('gtin');
      if (!Text::is_empty($gtin)) {
        $gtin = substr($gtin, -MODULE_CONTENT_PRODUCT_INFO_GTIN_LENGTH);

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
          'value' => 'col-sm-6',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'LENGTH' => [
          'title' => 'Length of GTIN',
          'value' => '13',
          'desc' => 'Length of GTIN. 14 (Industry Standard), 13 (eg ISBN codes and EAN UCC-13), 12 (UPC), 8 (EAN UCC-8)',
          'set_func' => "Config::select_one(['14', '13', '12', '8'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }
  }
