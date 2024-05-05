<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_addons extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_ADDONS_';

    public $content_width;

    function __construct() {
      parent::__construct();

      if ( $this->enabled ) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    function getOutput() {
      $feed = Web::load_xml('https://phoenixcart.org/forum/app.php/addons/feed');
      $Admin = Guarantor::ensure_global('Admin');

      $output = '<div class="table-responsive">';
        $output .= '<table class="table table-striped table-hover mb-0">';
          $output .= '<thead class="thead-dark">';
            $output .= '<tr>';
              $output .= '<th>' . $Admin->image('images/icon_phoenix.png', ['alt' => 'Phoenix']) . ' ' . MODULE_ADMIN_DASHBOARD_ADDONS_TITLE . '</th>';
              $output .= '<th>' . MODULE_ADMIN_DASHBOARD_ADDONS_AUTHOR . '</th>';
              $output .= '<th class="text-right">' . MODULE_ADMIN_DASHBOARD_ADDONS_UPDATED . '</th>';
            $output .= '</tr>';
          $output .= '</thead>';
          $output .= '<tbody>';

          $count = 0;
          foreach ($feed->entry as $item) {
            $dateTime = new DateTime($item->updated);
            $formattedDate = $dateTime->format('M d, Y');

            if ($count < MODULE_ADMIN_DASHBOARD_ADDONS_DISPLAY) {
              $output .= '<tr>';
                $output .= '<td><a href="' . $item->id . '" target="_blank" rel="noreferrer">' . $item->title . '</a></td>';
                $output .= '<td>' . $item->author->name . '</td>';
                $output .= '<td class="text-right">' . $formattedDate . '</td>';
              $output .= '</tr>';

              $count++;
            }
          }

          $output .= '</tbody>';
        $output .= '</table>';
      $output .= '</div>';

      $output .= $Admin->button(MODULE_ADMIN_DASHBOARD_ADDONS_VIEW_ALL, 'fas fa-external-link-alt', 'btn btn-success btn-block my-2', 'https://phoenixcart.org/forum/addons/', ['newwindow' => true]);

      return $output;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_ADDONS_STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to show this module on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_ADDONS_DISPLAY' => [
          'title' => 'Addons to display',
          'value' => '5',
          'desc' => 'This number of Addons will display, ordered by most recent.',
        ],
        'MODULE_ADMIN_DASHBOARD_ADDONS_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_ADDONS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '550',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
