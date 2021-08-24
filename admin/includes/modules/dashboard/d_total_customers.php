<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_total_customers {
    var $code = 'd_total_customers';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;
    var $content_width = 6;

    function __construct() {
      $this->title = MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_TITLE;
      $this->description = MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_DESCRIPTION;

      if ( defined('MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_STATUS') ) {
        $this->sort_order = MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_STATUS == 'True');
        $this->content_width = (int)MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_CONTENT_WIDTH;
      }
    }

    function getOutput() {
      $days = [];

      $chart_days = (int)MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_DAYS;

      for($i = 0; $i < $chart_days; $i++) {
        $days[date('M-d', strtotime('-'. $i .' days'))] = 0;
      }

      $orders_query = $GLOBALS['db']->query("select date_format(customers_info_date_account_created, '%b-%d') as dateday, count(*) as total from customers_info where date_sub(curdate(), interval '" . $chart_days . "' day) <= customers_info_date_account_created group by dateday");
      while ($orders = $orders_query->fetch_assoc()) {
        $days[$orders['dateday']] = $orders['total'];
      }

      $days = array_reverse($days, true);

      foreach ($days as $d => $r) {
        $plot_days[] = $d;
        $plot_customers[] = $r;
      }

      $plot_days = json_encode($plot_days);
      $plot_customers = json_encode($plot_customers);

      $table_header = MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_CHART_LINK;

      $output = <<<EOD
<div class="table-responsive">
  <table class="table mb-2">
    <thead class="thead-dark">
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
EOD;

      return $output;
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
          'title' => 'Content Width',
          'value' => '6',
          'desc' => 'What width container should the content be shown in? (12 = full width, 6 = half width).',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_TOTAL_CUSTOMERS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
