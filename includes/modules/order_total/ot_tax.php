<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ot_tax extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ORDER_TOTAL_TAX_';

    public $output = [];

    public function process() {
      global $order;

      foreach ($order->info['tax_groups'] as $key => $value) {
        if ($value > 0) {
          $this->output[] = [
            'title' => $key,
            'text' => Guarantor::ensure_global('currencies')->format($value, true, $order->info['currency'], $order->info['currency_value']),
            'value' => $value,
          ];
        }
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_ORDER_TOTAL_TAX_STATUS' => [
          'title' => 'Display Tax',
          'value' => 'True',
          'desc' => 'Do you want to display the order tax value?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ORDER_TOTAL_TAX_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '30',
          'desc' => 'Sort order of display.',
        ],
      ];
    }

  }
