<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class currencies {

    public $currencies;

    public function __construct() {
      $this->currencies = [];
      $currencies_query = $GLOBALS['db']->query("SELECT code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value FROM currencies");
      while ($currencies = $currencies_query->fetch_assoc()) {
        $this->currencies[$currencies['code']] = [
          'title' => $currencies['title'],
          'symbol_left' => $currencies['symbol_left'],
          'symbol_right' => $currencies['symbol_right'],
          'decimal_point' => $currencies['decimal_point'],
          'thousands_point' => $currencies['thousands_point'],
          'decimal_places' => (int)$currencies['decimal_places'],
          'value' => $currencies['value'],
        ];
      }
    }

    public function format($number, $calculate_currency_value = true, $currency_type = null, $currency_value = null) {
      if (empty($currency_type)) {
        $currency_type = $_SESSION['currency'] ?? DEFAULT_CURRENCY;
      }

      if ($calculate_currency_value) {
        $number *= ($currency_value ?? $this->currencies[$currency_type]['value']);
      }

      return $this->currencies[$currency_type]['symbol_left']
           . number_format(
               static::round($number, $this->currencies[$currency_type]['decimal_places']),
               $this->currencies[$currency_type]['decimal_places'],
               $this->currencies[$currency_type]['decimal_point'],
               $this->currencies[$currency_type]['thousands_point'])
           . $this->currencies[$currency_type]['symbol_right'];
    }

    public function calculate_price($products_price, $products_tax, $quantity = 1) {
      return static::round(tep_add_tax($products_price, $products_tax), $this->currencies[$_SESSION['currency'] ?? DEFAULT_CURRENCY]['decimal_places']) * $quantity;
    }

    public function is_set($code) {
      return !empty($this->currencies[$code]['value']) && is_numeric($this->currencies[$code]['value']);
    }

    public function get_value($code) {
      return $this->currencies[$code]['value'];
    }

    public function get_decimal_places($code) {
      return $this->currencies[$code]['decimal_places'];
    }

    public function display_price($products_price, $products_tax, $quantity = 1) {
      return $this->format($this->calculate_price($products_price, $products_tax, $quantity));
    }

    public function format_raw($number, $calculate_currency_value = true, $currency_type = null, $currency_value = null) {
      if (empty($currency_type)) {
        $currency_type = $_SESSION['currency'] ?? DEFAULT_CURRENCY;
      }

      if ($calculate_currency_value) {
        $number *= ($currency_value ?? $this->currencies[$currency_type]['value']);
      }

      return number_format(static::round($number, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], '.', '');
    }

    public function display_raw($products_price, $products_tax, $quantity = 1) {
      return $this->format_raw($this->calculate_price($products_price, $products_tax, $quantity));
    }

    public function set_currency() {
      if (!isset($_SESSION['currency']) || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $_SESSION['currency']) ) ) {
        if (isset($_GET['currency']) && $GLOBALS['currencies']->is_set($_GET['currency'])) {
          $_SESSION['currency'] = $_GET['currency'];
        } else {
          $_SESSION['currency'] = ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && $GLOBALS['currencies']->is_set(LANGUAGE_CURRENCY)) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
        }

        $GLOBALS['currency'] =& $_SESSION['currency'];
      }
    }

    public static function round($number, $precision) {
      $number = "$number";
      $location = strpos($number, '.');
// if there's a decimal point, increment the location to point after it
      if ((false === $location) || (strlen(substr($number, ++$location)) <= $precision)) {
// the number is already rounded sufficiently
        return $number;
      }

      $location += $precision;
      $next_digit = $number[$location];
      $number = substr($number, 0, $location);

      if ($next_digit < 5) {
// we already truncated (which rounds down)
        return $number;
      }

// otherwise we need to round up
      return ($precision < 1)
           ? $number + 1
           : $number + ('0.' . str_repeat(0, $precision - 1) . '1');
    }

  }
