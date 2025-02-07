<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class d_customers extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_';

    const REQUIRES = [
      'id',
      'sortable_name',
      'date_account_created',
    ];

    public $content_width;

    public function __construct() {
      parent::__construct();

      if ($this->enabled) {
        $this->content_width = $this->base_constant('CONTENT_WIDTH');
      }
    }

    function getOutput() {
      global $customer_data;

      $output = sprintf(<<<'EOTEXT'
<table class="table table-striped table-hover mb-2">
 <thead>
    <tr class="table-dark">
      <th>%s</th>
      <th class="text-end">%s</th>
    </tr>
  </thead>
  <tbody>
EOTEXT
, MODULE_ADMIN_DASHBOARD_CUSTOMERS_TITLE, MODULE_ADMIN_DASHBOARD_CUSTOMERS_DATE);

      $customer_limit = $this->base_constant('DISPLAY') ?? 6;
      $customers_query = $GLOBALS['db']->query(
        $customer_data->add_order_by(
          $customer_data->build_read(['id', 'sortable_name', 'date_account_created'], 'customers'), ['date_account_created' => 'DESC'])
        . ' LIMIT ' . (int)$customer_limit);
      while ($customers = $customers_query->fetch_assoc()) {
        $output .= sprintf(<<<'EOTEXT'
    <tr>
      <td><a href="%s">%s</a></td>
      <td class="text-end">%s</td>
    </tr>
EOTEXT
, $GLOBALS['Admin']->link('customers.php', 'cID=' . (int)$customer_data->get('id', $customers) . '&action=edit'),
  htmlspecialchars($customer_data->get('sortable_name', $customers)),
  Date::abridge($customer_data->get('date_account_created', $customers)));
      }

      $output .= "  </tbody>\n</table>";

      return $output;
    }

    public function get_parameters() {
      return [
        'MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS' => [
          'title' => 'Enable Customers Module',
          'value' => 'True',
          'desc' => 'Do you want to show the newest customers on the dashboard?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ADMIN_DASHBOARD_CUSTOMERS_DISPLAY' => [
          'title' => 'Customers to display',
          'value' => '5',
          'desc' => 'This number of Customers will display, ordered by most recent sign up.',
        ],
        'MODULE_ADMIN_DASHBOARD_CUSTOMERS_CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-md-6 mb-2',
          'desc' => 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).',
        ],
        'MODULE_ADMIN_DASHBOARD_CUSTOMERS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
