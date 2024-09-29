<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_cu_title extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_CU_TITLE_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      $content_width = MODULE_CONTENT_CU_TITLE_CONTENT_WIDTH;

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_CU_TITLE_STATUS' => [
          'title' => 'Enable Title Module',
          'value' => 'True',
          'desc' => 'Should this module be shown?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_CU_TITLE_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-12 mb-4',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        'MODULE_CONTENT_CU_TITLE_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '10',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
