<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class zone_js {

    protected $country;
    protected $prefix;

    public function __construct($country, $form, $field) {
      $this->country = $country;
      $this->prefix = "    $form.$field.options[";
    }

    public function __toString() {
      $zones_query = $GLOBALS['db']->query("SELECT zone_country_id, zone_name, zone_id FROM zones ORDER BY zone_country_id, zone_name");

      $country_js = '  if (' . $this->country . ' == "%d") {' . PHP_EOL;

      $country_id = false;
      $output_string = '';
      while ($zone = $zones_query->fetch_assoc()) {
        if ($zone['zone_country_id'] !== $country_id) {
          $country_id = $zone['zone_country_id'];
          $output_string .= sprintf($country_js, $country_id);
          $country_js = '  } else if (' . $this->country . ' == "%d") {' . PHP_EOL;
          $output_string .= $this->prefix . '0] = new Option("' . PLEASE_SELECT . '", "");' . PHP_EOL;
          $state_count = 1;
        }

        $output_string .= $this->prefix . $state_count . '] = new Option("' . $zone['zone_name'] . '", "' . $zone['zone_id'] . '");' . PHP_EOL;
        $state_count++;
      }
      $output_string .= '  } else {' . PHP_EOL
                      . $this->prefix . '0] = new Option("' . TYPE_BELOW . '", "");' . PHP_EOL
                      . '  }' . PHP_EOL;

      return $output_string;
    }

  }
