<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class nb_search extends abstract_block_module {

    const CONFIG_KEY_BASE = 'MODULE_NAVBAR_SEARCH_';

    public $group = 'navbar_modules_left';

    function getOutput() {
      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/block_template.php';
    }

    public function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your Navbar?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_PLACEMENT' => [
          'title' => 'Content Placement Group',
          'value' => 'Left',
          'desc' => 'Where should the module be loaded?  Lowest is loaded first, per Group.',
          'set_func' => "Config::select_one(['Home', 'Left', 'Center', 'Right'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '525',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
  