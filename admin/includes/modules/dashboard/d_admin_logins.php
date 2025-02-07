<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_admin_logins extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_';

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    function getOutput() {
      $output = '<table class="table table-striped table-hover mb-2">';
        $output .= '<thead class="table-dark">';
          $output .= '<tr>';
            $output .= '<th>' . MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_TITLE . '</th>';
            $output .= '<th class="text-end">'. MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_DATE . '</th>';
          $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        $logins_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT id, user_name, success, date_added
 FROM action_recorder
 WHERE module = 'ar_admin_login'
 ORDER BY date_added DESC LIMIT %d
EOSQL
, (int)MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_DISPLAY));
        while ($logins = $logins_query->fetch_assoc()) {
          $output .= '<tr>';
            $output .= '<td>'
                     . (($logins['success'] == '1') ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>')
                     . ' <a href="' . $GLOBALS['Admin']->link('action_recorder.php', 'module=ar_admin_login&aID=' . (int)$logins['id']) . '">' . htmlspecialchars($logins['user_name']) . '</a></td>';
            $output .= '<td class="text-end">' . $GLOBALS['date_time_formatter']->format((new Date($logins['date_added']))->get_timestamp()) . '</td>';
          $output .= '</tr>';
        }

        $output .= '</tbody>';
      $output .= '</table>';

      return $output;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_STATUS' => [
          'title' => 'Enable Administrator Logins Module',
          'value' => 'True',
          'desc' => 'Do you want to show the latest administrator logins on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_DISPLAY' => [
          'title' => 'Logins to display',
          'value' => '5',
          'desc' => 'This number of Logins will display, ordered by latest access.',
        ],
        'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '1000',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
