<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_monthly_sales extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_';

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    public function getOutput() {
      $months = [];
      $data = explode(',', MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_MONTHS);

      $table_header = MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_CHART_LINK;
      $step_size = MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_STEP;

      $chart_months = max($data);

      $month = time();
      $months[date('m/y')] = 0;
      for ($i = 2; $i <= $chart_months; $i++) {
        $month = strtotime('last month', $month);

        $months[date('m/y', $month)] = 0;
      }

      $orders_query = $GLOBALS['db']->query("select date_format(o.date_purchased, '%m/%y') as datemonth, sum(ot.value) as total from orders o, orders_total ot where date_sub(curdate(), interval '" . (int)$chart_months . "' month) <= o.date_purchased and o.orders_id = ot.orders_id and ot.class = 'ot_total' group by datemonth");
      while ($orders = $orders_query->fetch_assoc()) {
        $months[$orders['datemonth']] = $orders['total'];
      }

      $months = array_reverse($months, true);

      foreach ($months as $m => $r) {
        $plot_months[] = $m;
        $plot_sales[] = $r;
      }

      $m = [];
      foreach ($data as $a => $v) {
       $m[$v] = ['labels' => array_slice($plot_months, -$v),
                 'sales' => array_slice($plot_sales, -$v)];
      }

      $x = 1; $np = $tc = $s = '';
      foreach (array_keys($m) as $n) {
        $np_active = ($x == 1) ? ' active' : '';
        $tc_active = ($x == 1) ? ' show active' : '';

        $btn = sprintf(MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_MONTHS_BUTTON, $n);

$np .= <<<"EOD"
<li class="nav-item m-2">
  <a class="nav-link text-white{$np_active}" data-bs-toggle="tab" href="#sales_{$n}" role="tab">{$btn}</a>
</li>
EOD;

$tc .= <<<"EOD"
<div class="tab-pane fade{$tc_active}" id="sales_{$n}" role="tabpanel">
  <canvas id="monthlySales_{$n}"></canvas>
</div>
EOD;

$s .= <<<"EOD"
var ctx_{$n} = document.getElementById('monthlySales_{$n}').getContext('2d');
EOD;

        $x++;
      }

      foreach ($m as $n => $p) {
        $plot_labels = json_encode($m[$n]['labels']);
        $plot_data = json_encode($m[$n]['sales']);;
$s .= <<<"EOD"

var monthlySales_{$n} = new Chart(ctx_{$n}, {
  type: 'line',
  data: {
    labels: {$plot_labels},
    datasets: [{
        data: {$plot_data},
        backgroundColor: '#E67F2F',
        borderColor: '#000',
        pointRadius: 3,
        pointHoverRadius: 5,
        pointBackgroundColor: 'orange',
        borderWidth: 1
    }]
  },
  options: {
    scales: {yAxes: [{ticks: {stepSize: {$step_size}, beginAtZero: true}}]},
    responsive: true,
    title: {display: false},
    legend: {display: false},
    tooltips: {mode: 'index', intersect: false},
    hover: {mode: 'nearest', intersect: true}
  }
});

EOD;
      }

      return <<<"EOD"
<ul class="nav nav-pills bg-dark mb-1">
  <li class="nav-item m-2">
    <span class="nav-link text-white fw-bold">{$table_header}</span>
  </li>
  {$np}
</ul>
<div class="tab-content" style="min-height: 350px;">
  {$tc}
</div>

<script>
  {$s}
</script>
EOD;
    }

    protected function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to show this module on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_MONTHS' => [
          'title' => 'Months',
          'value' => '3,6,12',
          'desc' => 'Months to display.  Comma separated list will display each period in a tabbed interface.',
        ],
        'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_STEP' => [
          'title' => 'Step Size',
          'value' => '0',
          'desc' => 'This is the Y Axis Step Size in Currency Units.  Make this a number that is about half or so of your average monthly sales, you can play with this to suit the Graph output.',
        ],
        'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '50',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
