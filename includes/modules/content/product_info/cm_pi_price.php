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
        'MODULE_CONTENT_PI_PRICE_STATUS' => [
          'title' => 'Enable Price Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the product info page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_PI_PRICE_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '3',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_PI_PRICE_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '50',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
