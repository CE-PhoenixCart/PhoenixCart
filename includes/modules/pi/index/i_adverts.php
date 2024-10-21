<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class i_adverts extends abstract_module {

    const CONFIG_KEY_BASE = 'I_ADVERTS_';

    public $group = 'i_modules_b';

    function __construct() {
      parent::__construct();

      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_i_modular::display_layout() . '</div>';

      if ( $this->enabled ) {
        $this->group = 'i_modules_' . strtolower(I_ADVERTS_GROUP);
      }
    }

    function getOutput() {
      $i_adverts_linkage = adverts::get_grouped_adverts(I_ADVERTS_LINK);

      if (count($i_adverts_linkage) > 0) {
        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/block_template.php';
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Adverts Module',
          'value' => 'True',
          'desc' => 'Should this module be shown?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'GROUP' => [
          'title' => 'Module Display',
          'value' => 'A',
          'desc' => 'Where should this module display on the index page?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-8 mb-4',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'LINK' => [
          'title' => 'Advert Group',
          'value' => '',
          'desc' => 'Choose which Advert Group this module should display..',
          'set_func' => 'adverts::advert_pull_down_groups(',
          'use_func' => 'adverts::advert_get_group',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '85',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
