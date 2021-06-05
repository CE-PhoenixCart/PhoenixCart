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

    public $content_width = 6;

    function __construct() {
      parent::__construct();

      if ( $this->enabled ) {
        $this->content_width = (int)$this->base_constant('CONTENT_WIDTH');
      }
    }

    function getOutput() {
      $feed = Web::load_xml('https://feeds.feedburner.com/PhoenixAddons');
      $Admin = Guarantor::ensure_global('Admin');

      $output = '<div class="table-responsive">';
        $output .= '<table class="table table-striped table-hover mb-0">';
          $output .= '<thead class="thead-dark">';
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

      $output .= $Admin->button(MODULE_ADMIN_DASHBOARD_PHOENIX_VIEW_ALL, 'far fa-list-alt', 'btn btn-success btn-block my-2', $Admin->link('certified_addons.php'));

      return $output;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_STATUS' => [
          'title' => 'Enable Latest Add-Ons Module',
          'value' => 'True',
          'desc' => 'Do you want to show the latest Phoenix Club Add-Ons on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '6',
          'desc' => 'What width container should the content be shown in? (12 = full width, 6 = half width).',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '500',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
