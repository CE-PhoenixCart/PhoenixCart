<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_date_available extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PI_DATE_AVAILABLE_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $product;

      $date = $GLOBALS['product']->get('date_available');
      if ($date > date('Y-m-d H:i:s')) {
        $date = (MODULE_CONTENT_PI_DATE_AVAILABLE_STYLE === 'Long') ? Date::expound($date) : Date::abridge($date);

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
          'value' => 'col-sm-12',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'STYLE' => [
          'title' => 'Date Style',
          'value' => 'Long',
          'desc' => 'How should the date look?',
          'set_func' => "Config::select_one(['Long', 'Short'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '70',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }

