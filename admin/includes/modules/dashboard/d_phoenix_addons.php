<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_phoenix_addons extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_';

    public $content_width;

    function __construct() {
      parent::__construct();

      if ( $this->enabled ) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    function getOutput() {
      $feed = Web::load_xml('https://feeds.feedburner.com/PhoenixAddons');
      $Admin = Guarantor::ensure_global('Admin');

      $output = '<div class="table-responsive">';
        $output .= '<table class="table table-striped table-hover mb-0">';
          $output .= '<thead class="table-dark">';
            $output .= '<tr>';
              $output .= '<th colspan="2">' . $Admin->image('images/icon_phoenix.png', ['alt' => 'Phoenix']) . ' ' . MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_TITLE . '</th>';
            $output .= '</tr>';
          $output .= '</thead>';
          $output .= '<tbody>';

          foreach ($feed->channel->item as $item) {
            if ($item->highlight == 1) {
              $output .= '<tr>';
                $output .= '<td><a href="' . $item->link . '" target="_blank" rel="noreferrer">' . $item->link . '</a></td>';
                $output .= '<td>' . $item->title . '</td>';
              $output .= '</tr>';
            }
          }

          $output .= '</tbody>';
        $output .= '</table>';
      $output .= '</div>';
      
      $output .= '<div class="d-grid mt-2">';
        $output .= '<a class="btn btn-success" target="_blank" href="https://phoenixcart.org/forum/viewforum.php?f=22"><i class="far fa-list-alt me-1"></i>'. MODULE_ADMIN_DASHBOARD_PHOENIX_VIEW_ALL .'</a>';
      $output .= '</div>';

      return $output;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to show the latest Partner news on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '500',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
