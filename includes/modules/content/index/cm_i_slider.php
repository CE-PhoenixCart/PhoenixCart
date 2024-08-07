<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_i_slider extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_I_SLIDER_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      if ( !Text::is_empty(MODULE_CONTENT_I_SLIDER_GRP) ) {
        $cm_i_slider_adverts = adverts::get_grouped_adverts(MODULE_CONTENT_I_SLIDER_GRP);

        $cm_i_slider_controls = $cm_i_slider_output = $cm_i_indicator_output = $cm_i_indicator = $cm_i_slider_fade = null;

        if (count($cm_i_slider_adverts) > 0) {
          if (MODULE_CONTENT_I_SLIDER_FADE == 'Fade') {
            $cm_i_slider_fade = ' carousel-fade';
          }
          
          $cm_i_slider_interval = MODULE_CONTENT_I_SLIDER_INTERVAL;

          $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
          include 'includes/modules/content/cm_template.php';
        }
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Slider Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12 mb-4',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'GRP' => [
          'title' => 'Advert Group',
          'value' => '',
          'desc' => 'Choose which Advert Group this module should display..',
          'set_func' => 'adverts::advert_pull_down_groups(',
          'use_func' => 'adverts::advert_get_group',
        ],
        $this->config_key_base . 'CONTROLS' => [
          'title' => 'Enable Controls',
          'value' => 'True',
          'desc' => 'Do you want to show Left/Right Arrows?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'INDICATORS' => [
          'title' => 'Enable Indicators',
          'value' => 'True',
          'desc' => 'Do you want to show Indicators?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'FADE' => [
          'title' => 'Display Style',
          'value' => 'Fade',
          'desc' => 'Slide from the right or Fade In?',
          'set_func' => "Config::select_one(['Fade', 'Slide'], ",
        ],
        $this->config_key_base . 'INTERVAL' => [
          'title' => 'Interval',
          'value' => '10000',
          'desc' => 'How long a slide is seen before the next.  10000 = 10 seconds.',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '75',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
