<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_total_revenue extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_';

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    public function getOutput() {
      $days = [];

      $chart_days = (int)MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_DAYS;

      for($i = 0; $i < $chart_days; $i++) {
        $days[date('M-d', strtotime("-$i days"))] = 0;
      }

      $orders_query = $GLOBALS['db']->query("select date_format(o.date_purchased, '%b-%d') as dateday, sum(ot.value) as total from orders o, orders_total ot where date_sub(curdate(), interval '" . $chart_days . "' day) <= o.date_purchased and o.orders_id = ot.orders_id and ot.class = 'ot_total' group by dateday");
      while ($orders = $orders_query->fetch_assoc()) {
        $days[$orders['dateday']] = $orders['total'];
      }

      $days = array_reverse($days, true);

      foreach ($days as $d => $r) {
        $plot_days[] = $d;
        $plot_revenue[] = $r;
      }

      $plot_days = json_encode($plot_days);
      $plot_revenue = json_encode($plot_revenue);

      $table_header = MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_CHART_LINK;
      $step_size = MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_STEP;

      return <<<"EOD"
<div class="table-responsive">
  <table class="table mb-2">
    <thead class="thead-dark">
      <tr>
        <th>{$table_header}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><canvas id="totalRevenue" width="400" height="220"></canvas></td>
      </tr>
    </tbody>
  </table>
</div>

<script>
var ctx = document.getElementById('totalRevenue').getContext('2d');

var totalRevenue = new Chart(ctx, {
  type: 'line',
  data: {
    labels: {$plot_days},
    datasets: [{
        data: {$plot_revenue},
        backgroundColor: '#eee',
        borderColor: '#aaa',
        pointRadius: 5,
        pointHoverRadius: 5,
        pointBackgroundColor: 'orange',
        borderWidth: 1
    }]
  },
  options: {
    scales: {yAxes: [{ticks: {stepSize: {$step_size}}}]},
    responsive: true,
    title: {display: false},
    legend: {display: false},
    tooltips: {mode: 'index', intersect: false},
    hover: {mode: 'nearest', intersect: true}
  }
});
</script>
EOD;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_STATUS' => [
          'title' => 'Enable Total Revenue Module',
          'value' => 'True',
          'desc' => 'Do you want to show the total revenue chart on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_DAYS' => [
          'title' => 'Days',
          'value' => '7',
          'desc' => 'Days to display.',
        ],
        'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_STEP' => [
          'title' => 'Step Size',
          'value' => '50',
          'desc' => 'This is the Y Axis Step Size in Currency Units.  Make this a number that is about half or so of your average daily revenue, you can play with this to suit the Graph output.',
        ],
        'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '100',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
