<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ot_total extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ORDER_TOTAL_TOTAL_';

    public $output = [];

    public function process() {
      global $order;

      $this->output[] = [
        'title' => $this->title,
        'text' => '<strong>' . Guarantor::ensure_global('currencies')->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value']) . '</strong>',
        'value' => $order->info['total'],
      ];
    }

    protected function get_parameters() {
      return [
        'MODULE_ORDER_TOTAL_TOTAL_STATUS' => [
          'title' => 'Display Total',
          'value' => 'True',
          'desc' => 'Do you want to display the total order value?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '40',
          'desc' => 'Sort order of display.',
        ],
      ];
    }

  }
