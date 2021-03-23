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

    public $content_width = 6;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = (int)(self::get_constant('MODULE_ADMIN_DASHBOARD_VERSION_CHECK_CONTENT_WIDTH') ?? 6);
      }
    }

    function getOutput() {
      $current_version = Versions::get('Phoenix');

      $feed = Web::load_xml('https://feeds.feedburner.com/phoenixCartUpdate');
      $compared_version = preg_replace('/[^0-9.]/', '', $feed->channel->item[0]->title);

      $output = '<table class="table table-striped mb-2">';
        $output .= '<thead class="thead-dark">';
          $output .= '<tr>';
            $output .= '<th colspan="2">' . MODULE_ADMIN_DASHBOARD_VERSION_CHECK_TITLE . '</th>';
          $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        if (version_compare($current_version, $compared_version, '<')) {
          $link = Guarantor::ensure_global('Admin')->link('version_check.php');
          $output .= '<tr>';
            $output .= '<td class="bg-danger text-white">' . MODULE_ADMIN_DASHBOARD_VERSION_CHECK_UPDATE_AVAILABLE . '</td>';
            $output .= '<td class="bg-danger text-right"><a class="btn btn-info btn-sm" href="' . $link . '">' . MODULE_ADMIN_DASHBOARD_VERSION_CHECK_CHECK_NOW . '</a></td>';
          $output .= '</tr>';
        } else {
          $output .= '<tr>';
            $output .= '<td class="bg-success text-white" colspan="2">' . MODULE_ADMIN_DASHBOARD_VERSION_CHECK_IS_LATEST . '</td>';
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
          'set_func' => "tep_cfg_select_option(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '6',
          'desc' => 'What width container should the content be shown in? (12 = full width, 6 = half width).',
          'set_func' => "tep_cfg_select_option(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
