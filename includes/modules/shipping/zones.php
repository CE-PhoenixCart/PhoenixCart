<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class zones extends abstract_shipping_module {

    const CONFIG_KEY_BASE = 'MODULE_SHIPPING_ZONES_';

// CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
    const ZONE_COUNT = 1;

    protected $destination_zone = false;

    public function update_status_by($address) {
      if (!$this->enabled || (false !== $this->destination_zone) || !isset($address['country']['iso_code_2'])) {
        return;
      }

      for ($i = 1; $i <= static::ZONE_COUNT; $i++) {
        if (in_array($address['country']['iso_code_2'], explode(';', $this->base_constant("COUNTRIES_$i")))) {
          $this->destination_zone = $i;
          return;
        }
      }

      $this->enabled = false;
    }

    public function quote($method = '') {
      global $order, $shipping_weight, $shipping_num_boxes;
      $this->quotes = [
        'id' => $this->code,
        'module' => MODULE_SHIPPING_ZONES_TEXT_TITLE,
        'methods' => [],
      ];

      if (false !== $this->destination_zone) {
        $zones_table = preg_split('{[:,]}' , $this->base_constant("COST_{$this->destination_zone}"));
        for ($i = 0, $size = count($zones_table); $i < $size; $i += 2) {
          if ($shipping_weight <= $zones_table[$i]) {
            $this->quotes['methods'][] = [
              'id' => $this->code,
              'title' => sprintf(MODULE_SHIPPING_ZONES_TEXT_WAY,
                $order->delivery['country']['iso_code_2'],
                $shipping_weight),
              'cost' => ((float)$zones_table[$i+1] * $GLOBALS['shipping_num_boxes'])
                      + (float)$this->base_constant("HANDLING_{$this->destination_zone}"),
            ];
            break;
          }
        }

        if (!isset($this->quotes['methods'][0])) {
          error_log(sprintf('Weight [%d] larger than maximum in table [%s] for [%s].',
            $shipping_weight,
            $this->base_constant("COST_$dest_zone"),
            $order->delivery['country']['iso_code_2']));
        }
      }

      $this->quote_common();

      return $this->quotes;
    }

    protected function get_parameters() {
      $parameters = [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Zones Method',
          'value' => 'True',
          'desc' => 'Do you want to offer zone rate shipping?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'TAX_CLASS' => [
          'title' => 'Tax Class',
          'value' => '0',
          'desc' => 'Use the following tax class on the shipping fee.',
          'use_func' => 'Tax::get_class_title',
          'set_func' => 'Config::select_tax_class(',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display.',
        ],
      ];

      for ($i = 1; $i <= static::ZONE_COUNT; $i++) {
        $parameters = array_merge($parameters, [
          "{$this->config_key_base}COUNTRIES_$i" => [
            'title' => "Zone $i Countries",
            'value' => (($i == 1) ? 'US;CA' : ''),
            'desc' => "Semi-colon separated list of two character ISO country codes that are part of Zone $i.",
          ],
          "{$this->config_key_base}COST_$i" => [
            'title' => "Zone $i Shipping Table",
            'value' => '3:8.50,7:10.50,99:20.00',
            'desc' => <<<"EOT"
Shipping rates to Zone $i destinations based on a group of maximum order weights.
Example: 3:8.50,7:10.50,...
Weights less than or equal to 3 would cost 8.50 for Zone $i destinations.
EOT
          ],
          "{$this->config_key_base}HANDLING_$i" => [
            'title' => "Zone $i Handling Fee",
            'value' => '0',
            'desc' => 'Handling Fee for this shipping zone',
          ],
        ]);
      }

      return $parameters;
    }

  }
