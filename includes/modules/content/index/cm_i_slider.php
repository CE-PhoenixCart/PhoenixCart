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
          foreach ($cm_i_slider_adverts as $s => $a) {
            $carousel_active = $indicator_active = $s_link = $e_link = null;

            if ($s == 1) {
              $carousel_active = ' active';
              $indicator_active = ' class="border border-white bg-secondary rounded active" aria-current="true"';
            }

            if (!Text::is_empty($a['advert_url'])) {
              if (filter_var($a['advert_url'], FILTER_VALIDATE_URL)) {
                $s_link = '<a target="_blank" href="' . $a['advert_url'] . '">';
                $e_link = '</a>';
              } else {
                $fragment = url_query::parse($a['advert_fragment'] ?? '');

                $s_link = '<a href="' . $GLOBALS['Linker']->build($a['advert_url'], $fragment) . '">';
                $e_link = '</a>';
              }
            }

            $cm_i_slider_output .= '<div class="carousel-item' . $carousel_active . '">';
              $cm_i_slider_output .= $s_link;
                $cm_i_slider_output .= new Image('images/' . $a['advert_image']);
                if (!Text::is_empty($a['advert_html_text'])) {
                  $cm_i_slider_output .= '<div class="carousel-caption d-none d-md-block">';
                    $cm_i_slider_output .= $a['advert_html_text'];
                  $cm_i_slider_output .= '</div>';
                }
              $cm_i_slider_output .= $e_link;
            $cm_i_slider_output .= '</div>';

            if ($s == 1) {
              $cm_i_indicator .= '<li data-target="#cmislider" data-slide-to="0"' . $indicator_active . ' aria-label="Slide 1"></li>';
            } else {
              $cm_i_indicator .= '<li class="border border-white bg-secondary rounded" data-target="#cmislider" data-slide-to="' . $s-1 . '" aria-label="Slide ' . $s . '"></li>';
            }
          }

          if (MODULE_CONTENT_I_SLIDER_INDICATORS == 'True') {
            $cm_i_indicator_output .= '<ol class="carousel-indicators">';
              $cm_i_indicator_output .= $cm_i_indicator;
            $cm_i_indicator_output .= '</ol>';
          }

          if (MODULE_CONTENT_I_SLIDER_CONTROLS == 'True') {
            $previous = MODULE_CONTENT_I_SLIDER_CONTROLS_PREV;
            $next = MODULE_CONTENT_I_SLIDER_CONTROLS_NEXT;

            $cm_i_slider_controls .= <<<sc
            <a class="carousel-control-prev" href="#cmislider" role="button" data-slide="prev"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-prev-icon mt-1"></span></span><span class="sr-only">{$previous}</span></a><a class="carousel-control-next" href="#cmislider" role="button" data-slide="next"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-next-icon mt-1"></span></span><span class="sr-only">{$next}</span></a>
sc;
          }

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
