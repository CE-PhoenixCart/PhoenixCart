<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

    class cm_gdpr_orders extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_ORDERS_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      global $port_my_data;

      $orders_query = $GLOBALS['db']->query("select o.orders_id, o.date_purchased, ot.text from orders o left join orders_total ot on o.orders_id = ot.orders_id where o.customers_id = " . (int)$_SESSION['customer_id'] . " and ot.class = 'ot_total' order by orders_id desc");
      $num_orders = mysqli_num_rows($orders_query);

      $port_my_data['YOU']['ORDER']['COUNT'] = $num_orders;

      if ($num_orders > 0) {
        $o = 1;

        while ($orders = $orders_query->fetch_assoc()) {
          $order = new order((int)$orders['orders_id']);

          $port_my_data['YOU']['ORDER']['LIST'][$o]['ID'] = (int)$orders['orders_id'];
          $port_my_data['YOU']['ORDER']['LIST'][$o]['DATE'] = $order->info['date_purchased'];
          $port_my_data['YOU']['ORDER']['LIST'][$o]['STATUS'] = $order->info['orders_status'];
          $port_my_data['YOU']['ORDER']['LIST'][$o]['CURRENCY'] = $order->info['currency'];
          $port_my_data['YOU']['ORDER']['LIST'][$o]['PAYMENT'] = $order->info['payment_method'];
          $port_my_data['YOU']['ORDER']['LIST'][$o]['SHIPPING'] = $order->info['shipping_method'];
          $port_my_data['YOU']['ORDER']['LIST'][$o]['TOTAL'] = $order->info['total'];
          $port_my_data['YOU']['ORDER']['LIST'][$o]['PRODUCTS'] = [];
          $port_my_data['YOU']['ORDER']['LIST'][$o]['TOTALS'] = [];

          $p = 1;
          foreach ($order->products as $k => $v) {
            $port_my_data['YOU']['ORDER']['LIST'][$o]['PRODUCTS'][$p]['ID'] = $v['id'];
            $port_my_data['YOU']['ORDER']['LIST'][$o]['PRODUCTS'][$p]['QTY'] = $v['qty'];
            $port_my_data['YOU']['ORDER']['LIST'][$o]['PRODUCTS'][$p]['NAME'] = $v['name'];
            $port_my_data['YOU']['ORDER']['LIST'][$o]['PRODUCTS'][$p]['MODEL'] = $v['model'];
            $port_my_data['YOU']['ORDER']['LIST'][$o]['PRODUCTS'][$p]['PRICE'] = $v['final_price'];

            $p++;
          }

          $t = 1;
          foreach ($order->totals as $k => $v) {
            $port_my_data['YOU']['ORDER']['LIST'][$o]['TOTALS'][$t]['TITLE'] = $v['title'];
            $port_my_data['YOU']['ORDER']['LIST'][$o]['TOTALS'][$t]['TOTAL'] = strip_tags($v['text']);

            $t++;
          }

          $o++;
        }

        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Orders Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the GDPR page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12',
          'desc' => 'What width container should the content be shown in?',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '450',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
