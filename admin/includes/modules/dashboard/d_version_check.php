<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

    class d_version_check extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_';

    const REQUIRES = [];

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    public function getOutput() {
      $current_version = Versions::get('Phoenix');

      $feed = Web::load_xml('https://feeds.feedburner.com/phoenixCartUpdate');
      $compared_version = preg_replace('/[^0-9.]/', '', $feed->channel->item[0]->title);

      $output = '<table class="table table-striped mb-2">';
        $output .= '<thead class="table-dark">';
          $output .= '<tr>';
            $output .= '<th colspan="2">' . sprintf(MODULE_ADMIN_DASHBOARD_VERSION_CHECK_CURRENT, "$current_version") . '</th>';
          $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        if (version_compare($current_version, $compared_version, '<')) {
          $link = Guarantor::ensure_global('Admin')->link('version_check.php');
          $output .= '<tr>';
            $output .= '<td class="bg-danger text-white">' . sprintf(MODULE_ADMIN_DASHBOARD_VERSION_CHECK_UPDATE_AVAILABLE, "$compared_version") . '</td>';
            $output .= '<td class="bg-danger text-end"><a class="btn btn-info btn-sm" href="' . $link . '">' . MODULE_ADMIN_DASHBOARD_VERSION_CHECK_CHECK_NOW . '</a></td>';
          $output .= '</tr>';
        } else {
          $output .= '<tr>';
            $output .= '<td class="table-success" colspan="2">' . MODULE_ADMIN_DASHBOARD_VERSION_CHECK_IS_LATEST . '</td>';
          $output .= '</tr>';
        }
        $output .= '</tbody>';
      $output .= '</table>';

      return $output;
    }

    public function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_STATUS' => [
          'title' => 'Enable Version Check Module',
          'value' => 'True',
          'desc' => 'Do you want to show the version check results on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
