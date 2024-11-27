<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ot_shipping extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_ORDER_TOTAL_SHIPPING_';

    public $output = [];

    public static function can_ship_free_to($country_id) {
      return Country::match_classification(MODULE_ORDER_TOTAL_SHIPPING_DESTINATION, $country_id);
    }

    public static function is_eligible_free_shipping($country_id, $amount) {
      return defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING')
        && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'True')
        && self::can_ship_free_to($country_id)
        && ($amount >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER);
    }

    public function process() {
      global $order, $currencies;

      if (self::is_eligible_free_shipping($order->delivery['country_id'], $order->info['total'] - $order->info['shipping_cost'])) {
        $order->info['shipping_method'] = FREE_SHIPPING_TITLE;
        $order->info['total'] -= $order->info['shipping_cost'];
        $order->info['shipping_cost'] = 0;
      }

      if (!Text::is_empty($order->info['shipping_method'])) {
        $module = substr($_SESSION['shipping']['id'], 0, strpos($_SESSION['shipping']['id'], '_'));

        if (($GLOBALS[$module]->tax_class ?? 0) > 0) {
          $shipping_tax = Tax::get_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $shipping_tax_description = Tax::get_description($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);

          $order->info['tax'] += Tax::calculate($order->info['shipping_cost'], $shipping_tax);
          if (!isset($order->info['tax_groups']["$shipping_tax_description"])) {
            Guarantor::guarantee_subarray($order->info, 'tax_groups')["$shipping_tax_description"] = 0;
          }
          $order->info['tax_groups']["$shipping_tax_description"] += Tax::calculate($order->info['shipping_cost'], $shipping_tax);
          $order->info['total'] += Tax::calculate($order->info['shipping_cost'], $shipping_tax);

          if (DISPLAY_PRICE_WITH_TAX == 'true') {
            $order->info['shipping_cost'] += Tax::calculate($order->info['shipping_cost'], $shipping_tax);
          }
        }

        $this->output[] = [
          'title' => $order->info['shipping_method'],
          'text' => $currencies->format($order->info['shipping_cost'], true, $order->info['currency'], $order->info['currency_value']),
          'value' => $order->info['shipping_cost'],
        ];
      }
    }

    public function get_parameters() {
      return [
        'MODULE_ORDER_TOTAL_SHIPPING_STATUS' => [
          'title' => 'Display Delivery Cost',
          'value' => 'True',
          'desc' => 'Do you want to display the order delivery cost?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '20',
          'desc' => 'Sort order of display.',
        ],
        'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING' => [
          'title' => 'Allow Free Delivery',
          'value' => 'False',
          'desc' => 'Do you want to allow free delivery?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER' => [
          'title' => 'Free Delivery For Orders Over',
          'value' => '50',
          'desc' => 'Provide free delivery for orders over the set amount.',
          'use_func' => 'currencies->format',
        ],
        'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION' => [
          'title' => 'Provide Free Delivery For Orders Made',
          'value' => 'national',
          'desc' => 'Provide free delivery for orders sent to the set destination.',
          'set_func' => "Config::select_one(['national', 'international', 'both'], ",
        ],
      ];
    }

  }
