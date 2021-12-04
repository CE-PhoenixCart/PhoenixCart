<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class shoppingCart {

    public $contents, $total, $weight, $cartID, $content_type;

    function __construct() {
      $this->reset();
    }

    function restore_contents() {
      if (!isset($_SESSION['customer_id'])) {
        return false;
      }

// insert current cart contents in database
      if (is_array($this->contents)) {
        foreach (array_keys($this->contents) as $products_id) {
          $qty = $this->contents[$products_id]['qty'];
          $product_query = $GLOBALS['db']->query("SELECT products_id FROM customers_basket WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products_id) . "'");
          if (mysqli_num_rows($product_query)) {
            $GLOBALS['db']->query("UPDATE customers_basket SET customers_basket_quantity = '" . $GLOBALS['db']->escape($qty) . "' WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products_id) . "'");
          } else {
            $GLOBALS['db']->query("INSERT INTO customers_basket (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) VALUES (" . (int)$_SESSION['customer_id'] . ", '" . $GLOBALS['db']->escape($products_id) . "', '" . $GLOBALS['db']->escape($qty) . "', '" . date('Ymd') . "')");
            if (isset($this->contents[$products_id]['attributes'])) {
              foreach ($this->contents[$products_id]['attributes'] as $option => $value) {
                $GLOBALS['db']->query("INSERT INTO customers_basket_attributes (customers_id, products_id, products_options_id, products_options_value_id) VALUES (" . (int)$_SESSION['customer_id'] . ", '" . $GLOBALS['db']->escape($products_id) . "', " . (int)$option . ", " . (int)$value . ")");
              }
            }
          }
        }
      }

// reset per-session cart contents, but not the database contents
      $this->reset(false);

      $products_query = $GLOBALS['db']->query("SELECT products_id, customers_basket_quantity FROM customers_basket WHERE customers_id = " . (int)$_SESSION['customer_id']);
      while ($products = $products_query->fetch_assoc()) {
        $this->contents[$products['products_id']] = ['qty' => $products['customers_basket_quantity']];
// attributes
        $attributes_query = $GLOBALS['db']->query("SELECT products_options_id, products_options_value_id FROM customers_basket_attributes WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products['products_id']) . "'");
        while ($attributes = $attributes_query->fetch_assoc()) {
          $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
        }
      }

      $this->cleanup();

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function reset($reset_database = false) {
      $this->contents = [];
      $this->total = 0;
      $this->weight = 0;
      $this->content_type = false;

      if (isset($_SESSION['customer_id']) && $reset_database) {
        $GLOBALS['db']->query("DELETE FROM customers_basket WHERE customers_id = " . (int)$_SESSION['customer_id']);
        $GLOBALS['db']->query("DELETE FROM customers_basket_attributes WHERE customers_id = " . (int)$_SESSION['customer_id']);
      }

      unset($this->cartID);
      unset($_SESSION['cartID']);
    }

    function add_cart($products_id, $qty = '1', $attributes = '', $notify = true) {
      $products_id_string = Product::build_uprid($products_id, $attributes);
      $products_id = Product::build_prid($products_id_string);
      $product = product_by_id::build($products_id);

      if (defined('MAX_QTY_IN_CART') && (MAX_QTY_IN_CART > 0) && ((int)$qty > MAX_QTY_IN_CART)) {
        $qty = MAX_QTY_IN_CART;
      }

      if (!empty($attributes) && is_array($attributes)) {
        foreach ($attributes as $option => $value) {
          if (!is_numeric($option) || !is_numeric($value)) {
            return;
          }

          $check_query = $GLOBALS['db']->query("SELECT products_attributes_id FROM products_attributes WHERE products_id = " . (int)$products_id . " AND options_id = " . (int)$option . " AND options_values_id = " . (int)$value . " LIMIT 1");
          if (mysqli_num_rows($check_query) < 1) {
            return;
          }
        }
      } elseif ($product->get('has_attributes')) {
        return;
      }

      if (is_numeric($products_id) && is_numeric($qty)) {
        if ($product->get('status') == '1') {
          if ($notify) {
            $_SESSION['new_products_id_in_cart'] = $products_id;
          }

          if ($this->in_cart($products_id_string)) {
            $this->update_quantity($products_id_string, $qty, $attributes);
          } else {
            $this->contents[$products_id_string] = ['qty' => (int)$qty];

            if (isset($_SESSION['customer_id'])) {
              $GLOBALS['db']->query("INSERT INTO customers_basket (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) VALUES (" . (int)$_SESSION['customer_id'] . ", '" . $GLOBALS['db']->escape($products_id_string) . "', " . (int)$qty . ", '" . date('Ymd') . "')");
            }

            if (is_array($attributes)) {
              foreach ($attributes as $option => $value) {
                $this->contents[$products_id_string]['attributes'][$option] = $value;

                if (isset($_SESSION['customer_id'])) {
                  $GLOBALS['db']->query("INSERT INTO customers_basket_attributes (customers_id, products_id, products_options_id, products_options_value_id) VALUES (" . (int)$_SESSION['customer_id'] . ", '" . $GLOBALS['db']->escape($products_id_string) . "', " . (int)$option . ", " . (int)$value . ")");
                }
              }
            }
          }

          $this->cleanup();

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
          $this->cartID = $this->generate_cart_id();
        }
      }
    }

    function update_quantity($products_id, $quantity = '', $attributes = '') {
      $products_id_string = Product::build_uprid($products_id, $attributes);
      $products_id = Product::build_prid($products_id_string);

      if (defined('MAX_QTY_IN_CART') && (MAX_QTY_IN_CART > 0) && ((int)$quantity > MAX_QTY_IN_CART)) {
        $quantity = MAX_QTY_IN_CART;
      }

      if (is_array($attributes)) {
        foreach ($attributes as $option => $value) {
          if (!is_numeric($option) || !is_numeric($value)) {
            return;
          }
        }
      }

      if (is_numeric($products_id) && isset($this->contents[$products_id_string]) && is_numeric($quantity)) {
        $this->contents[$products_id_string] = ['qty' => (int)$quantity];

        if (isset($_SESSION['customer_id'])) {
          $GLOBALS['db']->query("UPDATE customers_basket SET customers_basket_quantity = " . (int)$quantity . " WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products_id_string) . "'");
        }

        if (is_array($attributes)) {
          foreach ($attributes as $option => $value) {
            $this->contents[$products_id_string]['attributes'][$option] = $value;

            if (isset($_SESSION['customer_id'])) {
              $GLOBALS['db']->query("UPDATE customers_basket_attributes SET products_options_value_id = " . (int)$value . " WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products_id_string) . "' AND products_options_id = " . (int)$option);
            }
          }
        }

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
        $this->cartID = $this->generate_cart_id();
      }
    }

    function cleanup() {
      foreach (array_keys($this->contents) as $key) {
        if ($this->contents[$key]['qty'] < 1) {
          unset($this->contents[$key]);

          if (isset($_SESSION['customer_id'])) {
            $GLOBALS['db']->query("DELETE FROM customers_basket WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($key) . "'");
            $GLOBALS['db']->query("DELETE FROM customers_basket_attributes WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($key) . "'");
          }
        }
      }
    }

// get total number of items in cart
    function count_contents() {
      $total_items = 0;
      if (is_array($this->contents)) {
        foreach (array_keys($this->contents) as $products_id) {
          $total_items += $this->get_quantity($products_id);
        }
      }

      return $total_items;
    }

    function get_quantity($products_id) {
      if (isset($this->contents[$products_id])) {
        return $this->contents[$products_id]['qty'];
      } else {
        return 0;
      }
    }

    function in_cart($products_id) {
      return isset($this->contents[$products_id]);
    }

    function remove($products_id) {
      unset($this->contents[$products_id]);
// remove from database
      if (isset($_SESSION['customer_id'])) {
        $GLOBALS['db']->query("DELETE FROM customers_basket WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products_id) . "'");
        $GLOBALS['db']->query("DELETE FROM customers_basket_attributes WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products_id) . "'");
      }

      $this->calculate();

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    function remove_all() {
      $this->reset();
    }

    function get_product_id_list() {
      $product_id_list = '';
      if (is_array($this->contents)) {
        foreach (array_keys($this->contents) as $products_id) {
          $product_id_list .= ', ' . $products_id;
        }
      }

      return substr($product_id_list, 2);
    }

    function calculate() {
      global $currencies;

      $this->total = 0;
      $this->weight = 0;
      if (!is_array($this->contents)) {
        return 0;
      }

      foreach (array_keys($this->contents) as $products_id) {
        $qty = $this->contents[$products_id]['qty'];

// products price
        $product_query = $GLOBALS['db']->query("SELECT products_id, products_price, products_tax_class_id, products_weight FROM products WHERE products_id = " . (int)$products_id);
        if ($product = $product_query->fetch_assoc()) {
          $prid = $product['products_id'];
          $products_tax = Tax::get_rate($product['products_tax_class_id']);
          $products_price = $product['products_price'];
          $products_weight = $product['products_weight'];

          $specials_query = $GLOBALS['db']->query("SELECT specials_new_products_price FROM specials WHERE products_id = " . (int)$prid . " AND status = 1");
          if (mysqli_num_rows($specials_query)) {
            $specials = $specials_query->fetch_assoc();
            $products_price = $specials['specials_new_products_price'];
          }

          $this->total += $currencies->calculate_price($products_price, $products_tax, $qty);
          $this->weight += ($qty * $products_weight);
        }

// attributes price
        if (isset($this->contents[$products_id]['attributes'])) {
          foreach ($this->contents[$products_id]['attributes'] as $option => $value) {
            $attribute_price_query = $GLOBALS['db']->query("SELECT options_values_price, price_prefix FROM products_attributes WHERE products_id = " . (int)$prid . " AND options_id = " . (int)$option . " AND options_values_id = " . (int)$value);
            $attribute_price = $attribute_price_query->fetch_assoc();
            if ($attribute_price['price_prefix'] == '+') {
              $this->total += $currencies->calculate_price($attribute_price['options_values_price'], $products_tax, $qty);
            } else {
              $this->total -= $currencies->calculate_price($attribute_price['options_values_price'], $products_tax, $qty);
            }
          }
        }
      }
    }

    function attributes_price($products_id) {
      $attributes_price = 0;

      if (isset($this->contents[$products_id]['attributes'])) {
        foreach ($this->contents[$products_id]['attributes'] as $option => $value) {
          $attribute_price_query = $GLOBALS['db']->query("SELECT options_values_price, price_prefix FROM products_attributes WHERE products_id = " . (int)$products_id . " AND options_id = " . (int)$option . " AND options_values_id = " . (int)$value);
          $attribute_price = $attribute_price_query->fetch_assoc();
          if ($attribute_price['price_prefix'] == '+') {
            $attributes_price += $attribute_price['options_values_price'];
          } else {
            $attributes_price -= $attribute_price['options_values_price'];
          }
        }
      }

      return $attributes_price;
    }

    function get_products() {
      if (!is_array($this->contents)) {
        return false;
      }

      $products_array = [];
      foreach (array_keys($this->contents) as $products_id) {
        $products_query = $GLOBALS['db']->query("SELECT p.products_id, pd.products_name, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_tax_class_id FROM products p, products_description pd WHERE p.products_id = " . (int)$products_id . " AND pd.products_id = p.products_id AND pd.language_id = " . (int)$_SESSION['languages_id']);
        if ($products = $products_query->fetch_assoc()) {
          $prid = $products['products_id'];
          $products_price = $products['products_price'];

          $specials_query = $GLOBALS['db']->query("SELECT specials_new_products_price FROM specials WHERE products_id = " . (int)$prid . " AND status = 1");
          if (mysqli_num_rows($specials_query)) {
            $specials = $specials_query->fetch_assoc();
            $products_price = $specials['specials_new_products_price'];
          }

          $products_array[] = [
            'id' => $products_id,
            'name' => $products['products_name'],
            'model' => $products['products_model'],
            'image' => $products['products_image'],
            'price' => $products_price,
            'quantity' => $this->contents[$products_id]['qty'],
            'weight' => $products['products_weight'],
            'final_price' => ($products_price + $this->attributes_price($products_id)),
            'tax_class_id' => $products['products_tax_class_id'],
            'attributes' => ($this->contents[$products_id]['attributes'] ?? null),
          ];
        }
      }

      return $products_array;
    }

    function show_total() {
      $this->calculate();

      return $this->total;
    }

    function show_weight() {
      $this->calculate();

      return $this->weight;
    }

    function generate_cart_id($length = 5) {
      return Password::create_random($length, 'digits');
    }

    function get_content_type() {
      $this->content_type = false;

      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
        foreach (array_keys($this->contents) as $products_id) {
          if (isset($this->contents[$products_id]['attributes'])) {
            foreach ($this->contents[$products_id]['attributes'] as $option => $value) {
              $virtual_check_query = $GLOBALS['db']->query("SELECT COUNT(*) AS total FROM products_attributes pa, products_attributes_download pad WHERE pa.products_id = " . (int)$products_id . " AND pa.options_values_id = " . (int)$value . " AND pa.products_attributes_id = pad.products_attributes_id");
              $virtual_check = $virtual_check_query->fetch_assoc();

              if ($virtual_check['total'] > 0) {
                switch ($this->content_type) {
                  case 'physical':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                  default:
                    $this->content_type = 'virtual';
                }
              } else {
                switch ($this->content_type) {
                  case 'virtual':
                    $this->content_type = 'mixed';

                    return $this->content_type;
                  default:
                    $this->content_type = 'physical';
                }
              }
            }
          } else {
            switch ($this->content_type) {
              case 'virtual':
                $this->content_type = 'mixed';

                return $this->content_type;
              default:
                $this->content_type = 'physical';
            }
          }
        }
      } else {
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }

  }
