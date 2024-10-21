<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class i_hero extends abstract_module {

    const CONFIG_KEY_BASE = 'I_HERO_';

    public $group = 'i_modules_b';

    public function __construct() {
      parent::__construct();

      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_i_modular::display_layout() . '</div>';

      if ( $this->enabled ) {
        $this->group = 'i_modules_' . strtolower(I_HERO_GROUP);
      }
    }

    public function getOutput() {
      $tpl_data = ['group' => $this->group, 'file' => __FILE__];
      include 'includes/modules/block_template.php';
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Hero Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the index page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'GROUP' => [
          'title' => 'Module Display',
          'value' => 'B',
          'desc' => 'Where should this module display on the index page?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => 'col-sm-12 mb-4',
          'desc' => 'What width container should the content be shown in?',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '89',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
  