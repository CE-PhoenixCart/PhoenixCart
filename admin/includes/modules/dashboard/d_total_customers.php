<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_total_customers extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_';

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    public function getOutput() {
      $chart_days = (int)MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_DAYS;

      $days = [];
      for($i = 0; $i < $chart_days; $i++) {
        $days[date('M-d', strtotime('-'. $i .' days'))] = 0;
      }

      $days = array_merge($days, array_column($GLOBALS['db']->fetch_all(sprintf(<<<'EOSQL'
SELECT DATE_FORMAT(customers_info_date_account_created, '%%b-%%d') AS dateday, COUNT(*) AS total
 FROM customers_info
 WHERE DATE_SUB(CURDATE(), INTERVAL %d DAY) <= customers_info_date_account_created
 GROUP BY dateday
EOSQL
        , $chart_days)), 'total', 'dateday'));

      $days = array_reverse($days, true);

      $plot_days = json_encode(array_keys($days));
      $plot_customers = json_encode(array_values($days));

      $table_header = MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_CHART_LINK;

      return <<<"EOHTML"
<div class="table-responsive">
  <table class="table mb-2">
    <thead class="table-dark">
      <tr>
        <th>{$table_header}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><canvas id="totalCustomers" width="400" height="220"></canvas></td>
      </tr>
    </tbody>
  </table>
</div>

<script>
var ctx = document.getElementById('totalCustomers').getContext('2d');

var totalCustomers = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: {$plot_days},
    datasets: [{
      data: {$plot_customers},
      backgroundColor: '#eee',
      borderColor: '#aaa',
      borderWidth: 1
    }]
  },
  options: {
    scales: {yAxes: [{ticks: {stepSize: 5}}]},
    responsive: true,
    title: {display: false},
    legend: {display: false},
    tooltips: {mode: 'index', intersect: false},
    hover: {mode: 'nearest', intersect: true}
  }
});
</script>
EOHTML;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_STATUS' => [
          'title' => 'Enable Total Customers Module',
          'value' => 'True',
          'desc' => 'Do you want to show the total customers chart on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_DAYS' => [
          'title' => 'Days',
          'value' => '7',
          'desc' => 'Days to display.',
        ],
        'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
