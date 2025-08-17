<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_orders extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_ORDERS_';

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    function getOutput() {
      $orders_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT o.orders_id, o.customers_name, COALESCE(o.last_modified, o.date_purchased) AS date_last_modified, s.orders_status_name, ot.text AS order_total
 FROM orders o INNER JOIN orders_total ot ON o.orders_id = ot.orders_id INNER JOIN orders_status s ON o.orders_status = s.orders_status_id AND s.language_id = %d
 WHERE ot.class = 'ot_total'
 ORDER BY date_last_modified DESC
 LIMIT %d
EOSQL
        , (int)$_SESSION['languages_id'], (int)MODULE_ADMIN_DASHBOARD_ORDERS_DISPLAY));

      $output = '<div class="h-100 card p-1">';
      $output .= '<div class="table-responsive">';
        $output .= '<table class="table table-striped table-hover mb-2">';
          $output .= '<thead class="table-dark">';
            $output .= '<tr>';
              $output .= '<th>' . MODULE_ADMIN_DASHBOARD_ORDERS_TITLE . '</th>';
              $output .= '<th>' . MODULE_ADMIN_DASHBOARD_ORDERS_TOTAL . '</th>';
              $output .= '<th>' . MODULE_ADMIN_DASHBOARD_ORDERS_DATE . '</th>';
              $output .= '<th class="text-end">' . MODULE_ADMIN_DASHBOARD_ORDERS_ORDER_STATUS . '</th>';
            $output .= '</tr>';
          $output .= '</thead>';
          $output .= '<tbody>';

          while ($order = $orders_query->fetch_assoc()) {
            $output .= '<tr>';
              $output .= '<td><a href="' . $GLOBALS['Admin']->link('orders.php', 'oID=' . (int)$order['orders_id'] . '&action=edit') . '">' . htmlspecialchars($order['customers_name']) . '</a></td>';
              $output .= '<td>' . strip_tags($order['order_total']) . '</td>';
              $output .= '<td>' . Date::abridge($order['date_last_modified']) . '</td>';
              $output .= '<td class="text-end">' . $order['orders_status_name'] . '</td>';
            $output .= '</tr>';
          }

          $output .= '</tbody>';
        $output .= '</table>';
      $output .= '</div>';
      $output .= '</div>';

      return $output;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_ORDERS_STATUS' => [
          'title' => 'Enable Orders Module',
          'value' => 'True',
          'desc' => 'Do you want to show the latest orders on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_ORDERS_DISPLAY' => [
          'title' => 'Orders to display',
          'value' => '5',
          'desc' => 'This number of Orders will display, ordered by most recent.',
        ],
        'MODULE_ADMIN_DASHBOARD_ORDERS_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_ORDERS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '300',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
