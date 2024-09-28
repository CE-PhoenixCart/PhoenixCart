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

    public function __construct() {
      $this->reset();
    }

    public function restore_contents() {
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

    public function reset($reset_database = false) {
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

    public function add_cart($products_id, $qty = 1, $attributes = null, $notify = true) {
      if ($products_id instanceof Product) {
        $product = $products_id;
        $products_id = $product->get('id');
      }

      $products_id_string = Product::build_uprid($products_id, $attributes);
      $products_id = Product::build_prid($products_id_string);

      if (!isset($product)) {
        $product = product_by_id::build($products_id);
      }

      if (defined('MAX_QTY_IN_CART') && (MAX_QTY_IN_CART > 0) && ((int)$qty > MAX_QTY_IN_CART)) {
        $qty = MAX_QTY_IN_CART;
      }

      if (!empty($attributes) && is_array($attributes)) {
        foreach ($attributes as $option => $value) {
          if (!is_numeric($option) || !is_numeric($value)) {
            return false;
          }

          $check_query = $GLOBALS['db']->query("SELECT products_attributes_id FROM products_attributes WHERE products_id = " . (int)$products_id . " AND options_id = " . (int)$option . " AND options_values_id = " . (int)$value . " LIMIT 1");
          if (mysqli_num_rows($check_query) < 1) {
            return false;
          }
        }
      } elseif ($product->get('has_attributes')) {
        return false;
      }

      if (is_numeric($products_id) && is_numeric($qty)) {
        $check_product_query = $GLOBALS['db']->query("SELECT products_status FROM products WHERE products_id = " . (int)$products_id);
        $check_product = $check_product_query->fetch_assoc();

        if ($product->get('status')) {
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

      return $product->get('name');
    }

    public function update_quantity($products_id, $quantity, $attributes = null) {
      $products_id_string = Product::build_uprid($products_id, $attributes);
      $products_id = Product::build_prid($products_id_string);

      if (defined('MAX_QTY_IN_CART') && (MAX_QTY_IN_CART > 0) && ((int)$quantity > MAX_QTY_IN_CART)) {
        $quantity = MAX_QTY_IN_CART;
      }

      foreach (($attributes ?? []) as $option => $value) {
        if (!is_numeric($option) || !is_numeric($value)) {
          return;
        }
      }

      if (is_numeric($products_id) && isset($this->contents[$products_id_string]) && is_numeric($quantity)) {
        $this->contents[$products_id_string] = ['qty' => (int)$quantity];

        if (isset($_SESSION['customer_id'])) {
          $GLOBALS['db']->query(sprintf(<<<'EOSQL'
UPDATE customers_basket
 SET customers_basket_quantity = %d
 WHERE customers_id = %d AND products_id = '%s'
EOSQL
            , (int)$quantity, (int)$_SESSION['customer_id'], $GLOBALS['db']->escape($products_id_string)));
        }

        foreach (($attributes ?? []) as $option => $value) {
          $this->contents[$products_id_string]['attributes'][$option] = $value;

          if (isset($_SESSION['customer_id'])) {
            $GLOBALS['db']->query(sprintf(<<<'EOSQL'
UPDATE customers_basket_attributes
 SET products_options_value_id = %d
 WHERE customers_id = %d AND products_id = '%s' AND products_options_id = %d
EOSQL
              , (int)$value, (int)$_SESSION['customer_id'], $GLOBALS['db']->escape($products_id_string), (int)$option));
          }
        }

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
        $this->cartID = $this->generate_cart_id();
      }
    }

    public function cleanup() {
      foreach (array_keys($this->contents) as $product_id) {
        if ($this->contents[$product_id]['qty'] < 1) {
          unset($this->contents[$product_id]);

          if (isset($_SESSION['customer_id'])) {
            $GLOBALS['db']->query("DELETE FROM customers_basket WHERE products_id = '" . $GLOBALS['db']->escape($product_id) . "' AND customers_id = " . (int)$_SESSION['customer_id']);
            $GLOBALS['db']->query("DELETE FROM customers_basket_attributes WHERE products_id = '" . $GLOBALS['db']->escape($product_id) . "' AND customers_id = " . (int)$_SESSION['customer_id']);
          }
        }
      }
    }

// get total number of items in cart
    public function count_contents() {
      return array_sum(array_column($this->contents, 'qty'));
    }

    public function get_quantity($products_id) {
      return $this->contents[$products_id]['qty'] ?? 0;
    }

    public function in_cart($products_id) {
      return isset($this->contents[$products_id]);
    }

    protected function _remove($products_id) {
      unset($this->contents[$products_id]);
// remove from database
      if (isset($_SESSION['customer_id'])) {
        $GLOBALS['db']->query("DELETE FROM customers_basket WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products_id) . "'");
        $GLOBALS['db']->query("DELETE FROM customers_basket_attributes WHERE customers_id = " . (int)$_SESSION['customer_id'] . " AND products_id = '" . $GLOBALS['db']->escape($products_id) . "'");
      }
    }

    public function remove($products_id) {
      $this->_remove($products_id);
      $this->calculate();

// assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
      $this->cartID = $this->generate_cart_id();
    }

    public function remove_all() {
      $this->reset();
    }

    public function get_product_id_list() {
      return implode(', ', array_keys($this->contents));
    }

    public function calculate() {
      $this->total = 0;
      $this->weight = 0;

      foreach ($this->contents as $product_id => $data) {
        $qty = $data['qty'];

// product price
        $product = product_by_id::build(Product::build_prid($product_id));
        if ($product->get('status')) {
          $products_price = $product->get('base_price');

// attributes price
          if (!empty($data['attributes'])) {
            $products_price += $this->attributes_price($product_id, $product->get('attributes'));
          }

          $this->total += $GLOBALS['currencies']->calculate_price($products_price, $product->get('tax_rate'), $qty);
          $this->weight += ($qty * $product->get('weight'));
        } else {
          $this->_remove($product_id);
          $GLOBALS['messageStack']->add('product_action', sprintf(PRODUCT_REMOVED, Product::fetch_name($product_id)));
        }

      }
    }

    public function attributes_price($product_id, $attributes) {
      $attributes_price = 0;

      foreach (($this->contents[$product_id]['attributes'] ?? []) as $option => $value) {
        if ($attributes[$option]['values'][$value]['prefix'] == '+') {
          $attributes_price += $attributes[$option]['values'][$value]['price'];
        } else {
          $attributes_price -= $attributes[$option]['values'][$value]['price'];
        }
      }

      return $attributes_price;
    }

    public function get_products() {
      $products = [];
      foreach ($this->contents as $product_id => $data) {
        $product = product_by_id::build($prid = Product::build_prid($product_id));
        if ($product->get('status')) {
          $product->set('uprid', $product_id);
          $product->set('quantity', $data['qty']);
          $product->set('link', $GLOBALS['Linker']->build('product_info.php', ['products_id' => $product_id]));
          $product->set('final_price', $product->get('base_price') + $this->attributes_price($product_id, $product->get('attributes')));
          $product->set('attribute_selections', $data['attributes'] ?? null);

          $products[] = $product;
        } else {
          $this->_remove($product_id);
          $GLOBALS['messageStack']->add('product_action', sprintf(PRODUCT_REMOVED, Product::fetch_name($prid)));
        }
      }

      return array_reverse($products);
    }

    public function show_total() {
      $this->calculate();

      return $this->total;
    }

    public function show_weight() {
      $this->calculate();

      return $this->weight;
    }

    public function generate_cart_id($length = 5) {
      return Password::create_random($length, 'digits');
    }

    protected function has_virtual_attributes($product_id, $attributes) {
      foreach ($attributes as $value) {
        $virtual_check_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT COUNT(*) AS total
 FROM products_attributes pa INNER JOIN products_attributes_download pad ON pa.products_attributes_id = pad.products_attributes_id
 WHERE pa.products_id = %d AND pa.options_values_id = %d
EOSQL
          , (int)$product_id, (int)$value));
        $virtual_check = $virtual_check_query->fetch_assoc();

        if ($virtual_check['total'] > 0) {
          return true;
        }
      }

      return false;
    }

    public function get_content_type() {
      if ( (DOWNLOAD_ENABLED == 'true') && ($this->count_contents() > 0) ) {
        $this->content_type = false;

        foreach ($this->contents as $id => $data) {
          if (isset($data['attributes']) && $this->has_virtual_attributes($id, $data['attributes'])) {
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
        $this->content_type = 'physical';
      }

      return $this->content_type;
    }

  }
