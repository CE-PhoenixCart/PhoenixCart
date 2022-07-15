<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class flat extends abstract_shipping_module {

    const CONFIG_KEY_BASE = 'MODULE_SHIPPING_FLAT_';

// class methods
    public function quote($method = '') {
      global $order;

      $this->quotes = [
        'id' => $this->code,
        'module' => MODULE_SHIPPING_FLAT_TEXT_TITLE,
        'methods' => [[
          'id' => $this->code,
          'title' => MODULE_SHIPPING_FLAT_TEXT_WAY,
          'cost' => (float)$this->base_constant('COST') + $this->calculate_handling(),
        ]],
      ];

      $this->quote_common();

      return $this->quotes;
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Flat Shipping',
          'value' => 'True',
          'desc' => 'Do you want to offer flat rate shipping?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'COST' => [
          'title' => 'Shipping Cost',
          'value' => '5.00',
          'desc' => 'The shipping cost for all orders using this shipping method.',
        ],
        $this->config_key_base . 'TAX_CLASS' => [
          'title' => 'Tax Class',
          'value' => '0',
          'desc' => 'Use the following tax class on the shipping fee.',
          'use_func' => 'Tax::get_class_title',
          'set_func' => 'Config::select_tax_class(',
        ],
        $this->config_key_base . 'ZONE' => [
          'title' => 'Shipping Zone',
          'value' => '0',
          'desc' => 'If a zone is selected, only enable this shipping method for that zone.',
          'use_func' => 'geo_zone::fetch_name',
          'set_func' => 'Config::select_geo_zone(',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display.',
        ],
      ];
    }

  }
