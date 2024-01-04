<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ot_subtotal extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ORDER_TOTAL_SUBTOTAL_';

    public $output = [];

    public function process() {
      global $order;

      $this->output[] = [
        'title' => $this->title,
        'text' => Guarantor::ensure_global('currencies')->format($order->info['subtotal'], true, $order->info['currency'], $order->info['currency_value']),
        'value' => $order->info['subtotal'],
      ];
    }

    protected function get_parameters() {
      return [
        'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS' => [
          'title' => 'Display Sub-Total',
          'value' => 'True',
          'desc' => 'Do you want to display the order sub-total cost?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '10',
          'desc' => 'Sort order of display.',
        ],
      ];
    }

  }
