<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cart_order_builder {

    public static $column_keys = null;

    protected $order;

    public function __construct(&$order) {
      if (is_null(static::$column_keys)) {
        static::$column_keys = [
          'qty' => 'quantity',
          'name' => 'name',
          'model' => 'model',
          'price' => 'price',
          'final_price' => 'final_price',
          'weight' => 'weight',
          'id' => 'id',
        ];
        $parameters = [
          'column_keys' => &static::$column_keys,
        ];
        $GLOBALS['all_hooks']->cat('cartOrderProductColumns', $parameters);
      }

      $this->order =& $order;
    }

    public function build_info() {
      $this->order->info = [
        'order_status' => DEFAULT_ORDERS_STATUS_ID,
        'currency' => $_SESSION['currency'],
        'currency_value' => $GLOBALS['currencies']->currencies[$_SESSION['currency']]['value'],
        'payment_method' => $_SESSION['payment'] ?? null,
        'shipping_method' => $_SESSION['shipping']['title'] ?? null,
        'shipping_cost' => $_SESSION['shipping']['cost'] ?? null,
        'subtotal' => 0,
        'tax' => 0,
        'tax_groups' => [],
        'comments' => ($_SESSION['comments'] ?? ''),
      ];

      if (is_string($_SESSION['payment'] ?? null) && (($GLOBALS[$_SESSION['payment']] ?? null) instanceof $_SESSION['payment'])) {
        $this->order->info['payment_method'] = $GLOBALS[$_SESSION['payment']]->public_title ?? $GLOBALS[$_SESSION['payment']]->title;

        if ( is_numeric($GLOBALS[$_SESSION['payment']]->order_status ?? null) && ($GLOBALS[$_SESSION['payment']]->order_status > 0) ) {
          $this->order->info['order_status'] = $GLOBALS[$_SESSION['payment']]->order_status;
        }
      }
    }

    public function build_addresses() {
      global $customer;

      $this->order->customer = $customer->fetch_to_address(0);
      $this->order->billing = $customer->fetch_to_address($_SESSION['billto'] ?? null);

      if ( !$_SESSION['sendto'] && ('virtual' !== $this->order->content_type) ) {
        $_SESSION['sendto'] = $customer->get('default_sendto');
      }

      $this->order->delivery = $customer->fetch_to_address($_SESSION['sendto']);
    }

    public function build_tax_address() {
      if ('virtual' === $this->order->content_type) {
        return [
          'entry_country_id' => $GLOBALS['customer_data']->get('country_id', $this->order->billing),
          'entry_zone_id' => $GLOBALS['customer_data']->get('zone_id', $this->order->billing),
        ];
      }

      return [
        'entry_country_id' => $GLOBALS['customer_data']->get('country_id', $this->order->delivery),
        'entry_zone_id' => $GLOBALS['customer_data']->get('zone_id', $this->order->delivery),
      ];
    }

    public function update_per_product($current) {
      $shown_price = $GLOBALS['currencies']->calculate_price($current['final_price'], $current['tax'], $current['qty']);
      $this->order->info['subtotal'] += $shown_price;

      if (DISPLAY_PRICE_WITH_TAX == 'true') {
        $tax = $shown_price - ($shown_price / ((($current['tax'] < 10) ? "1.0" : "1.") . str_replace('.', '', $current['tax'])));
      } else {
        $tax = ($current['tax'] / 100) * $shown_price;
      }
      $this->order->info['tax'] += $tax;

      $tax_description = $current['tax_description'];
      if (!isset($this->order->info['tax_groups']["$tax_description"])) {
        $this->order->info['tax_groups']["$tax_description"] = 0;
      }
      $this->order->info['tax_groups']["$tax_description"] += $tax;
    }

    public function build_attributes($product) {
      $options = $product->get('attributes');
      $attributes = [];
      foreach ($product->get('attribute_selections') as $option => $value) {
        $attribute = $options[$option]['values'][$value];
        $attribute['value_id'] = $value;
        $attribute['option_id'] = $option;
        $attribute['value'] = $attribute['name'];
        $attribute['option'] = $options[$option]['name'];

        $attributes[] = $attribute;
      }

      return $attributes;
    }

    public function build_products() {
      $tax_address = $this->build_tax_address();

      foreach ($_SESSION['cart']->get_products() as $product) {
        $current = [];
        foreach (static::$column_keys as $order_key => $cart_key) {
          $current[$order_key] = $product->get($cart_key);
        }
        $current['tax'] = Tax::get_rate($product->get('tax_class_id'), $tax_address['entry_country_id'], $tax_address['entry_zone_id']);
        $current['tax_description'] = Tax::get_description($product->get('tax_class_id'), $tax_address['entry_country_id'], $tax_address['entry_zone_id']);

        if ($product->get('attributes')) {
          $current['attributes'] = $this->build_attributes($product);
        }

        $this->update_per_product($current);

        $this->order->products[] = $current;
      }
    }

    public static function build(&$order) {
      $builder = new cart_order_builder($order);
      $builder->build_info();

      $order->content_type = $_SESSION['cart']->get_content_type();
      $builder->build_addresses();

      $builder->build_products();

      $order->info['total'] = $order->info['subtotal'] + $order->info['shipping_cost'];
      if (DISPLAY_PRICE_WITH_TAX != 'true') {
        $order->info['total'] += $order->info['tax'];
      }

      $parameters = [
        'builder' => $builder,
        'order' => &$order,
      ];
      $GLOBALS['all_hooks']->cat('cartOrderBuild', $parameters);

      return $order;
    }

  }
