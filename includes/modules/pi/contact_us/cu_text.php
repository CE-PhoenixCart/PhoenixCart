<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cu_text extends abstract_module {

    const CONFIG_KEY_BASE = 'CU_TEXT_';

    public $group = 'cu_modules_b';
    public $content_width;

    public function __construct() {
      parent::__construct();

      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_cu_modular::display_layout() . '</div>';

      if ( $this->enabled ) {
        $this->group = 'cu_modules_' . strtolower(CU_TEXT_GROUP);
        $this->content_width = CU_TEXT_CONTENT_WIDTH;
      }
    }

    public function getOutput() {
      $text = CU_TEXT_PUBLIC_TEXT;
      
      if (!Text::is_empty($text)) {
        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/block_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'CU_TEXT_STATUS' => [
          'title' => 'Enable Text',
          'value' => 'True',
          'desc' => 'Should this module be shown?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'CU_TEXT_GROUP' => [
          'title' => 'Module Display',
          'value' => 'B',
          'desc' => 'Where should this module display?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        'CU_TEXT_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-12 mb-3',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        'CU_TEXT_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '120',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
