<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ot_loworderfee extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ORDER_TOTAL_LOWORDERFEE_';

    public $output = [];

    public function process() {
      global $order;

      if ( (MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE === 'True')
          && Country::match_classification(MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION, $order->delivery['country_id'])
          && ( ($order->info['total'] - $order->info['shipping_cost']) < MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER) )
      {
        $tax = Tax::get_rate(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
        $tax_description = Tax::get_description(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $order->delivery['country']['id'], $order->delivery['zone_id']);
        $tax_price = Tax::price(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);
        $tax_calculation = Tax::calculate(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);

        $order->info['tax'] += $tax_calculation;
        $order->info['tax_groups']["$tax_description"] += $tax_calculation;
        $order->info['total'] += MODULE_ORDER_TOTAL_LOWORDERFEE_FEE + $tax_calculation;

        $this->output[] = [
          'title' => $this->title . ':',
          'text' => Guarantor::ensure_global('currencies')->format($tax_price, true, $order->info['currency'], $order->info['currency_value']),
          'value' => $tax_price,
        ];
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS' => [
          'title' => 'Display Low Order Fee',
          'value' => 'True',
          'desc' => 'Do you want to display the low order fee?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '4',
          'desc' => 'Sort order of display.',
        ],
        'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE' => [
          'title' => 'Allow Low Order Fee',
          'value' => 'False',
          'desc' => 'Do you want to allow low order fees?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER' => [
          'title' => 'Order Fee For Orders Under',
          'value' => '50',
          'desc' => 'Add the low order fee to orders under this amount.',
          'use_func' => 'currencies->format',
        ],
        'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE' => [
          'title' => 'Order Fee',
          'value' => '5',
          'desc' => 'Low order fee.',
          'use_func' => 'currencies->format',
        ],
        'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION' => [
          'title' => 'Attach Low Order Fee On Orders Made',
          'value' => 'both',
          'desc' => 'Attach low order fee for orders sent to the set destination.',
          'set_func' => "Config::select_one(['national', 'international', 'both'], ",
        ],
        'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS' => [
          'title' => 'Tax Class',
          'value' => '0',
          'desc' => 'Use the following tax class on the low order fee.',
          'use_func' => 'Tax::get_class_title',
          'set_func' => 'Config::select_tax_class(',
        ],
      ];
    }

  }
