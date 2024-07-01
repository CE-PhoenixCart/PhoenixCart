<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_price extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PI_PRICE_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $product;

      $price = $product->get('is_special')
             ? sprintf(MODULE_CONTENT_PI_PRICE_DISPLAY_SPECIAL,
                 $product->format(),
                 $product->format('price'))
             : sprintf(MODULE_CONTENT_PI_PRICE_DISPLAY,
                 $product->format());

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
          'value' => 'col-sm-3',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '50',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
