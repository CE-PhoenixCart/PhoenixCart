<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_cs_downloads extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      if ( 'true' === DOWNLOAD_ENABLED ) {
        ob_start();
        extract($GLOBALS, EXTR_SKIP);
        
        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_STATUS' => [
          'title' => 'Enable Product Downloads Module',
          'value' => 'True',
          'desc' => 'Should ordered product download links be shown on the checkout success page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
