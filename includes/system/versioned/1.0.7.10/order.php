<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class order {

    public $info = [];
    public $totals = [];
    public $products = [];
    public $customer = [];
    public $delivery = [];
    public $billing = [];
    public $content_type, $id;

    public function __construct($order_id = null) {
      if (empty($order_id)) {
        cart_order_builder::build($this);
      } else {
        $this->set_id($order_id);
        database_order_builder::build($this);
      }

      $GLOBALS['all_hooks']->cat('constructOrder', $this);
    }

    public function has_id() {
      return isset($this->id);
    }

    public function get_id() {
      return $this->id;
    }

    public function set_id($order_id) {
      $this->id = Text::input($order_id);
    }

    public static function remove($order_id, $restock = false) {
      if ('on' === $restock) {
        $GLOBALS['db']->query(sprintf(<<<'EOSQL'
UPDATE products p INNER JOIN orders_products op ON p.products_id = op.products_id
  SET p.products_quantity = p.products_quantity + op.products_quantity,
      p.products_ordered = p.products_ordered - op.products_quantity
  WHERE op.orders_id = %d
EOSQL
          , (int)$order_id));
      }

      $GLOBALS['db']->query("DELETE FROM orders_products_download WHERE orders_id = " . (int)$order_id);
      $GLOBALS['db']->query("DELETE FROM orders_products_attributes WHERE orders_id = " . (int)$order_id);
      $GLOBALS['db']->query("DELETE FROM orders_products WHERE orders_id = " . (int)$order_id);
      $GLOBALS['db']->query("DELETE FROM orders_status_history WHERE orders_id = " . (int)$order_id);
      $GLOBALS['db']->query("DELETE FROM orders_total WHERE orders_id = " . (int)$order_id);
      $GLOBALS['db']->query("DELETE FROM orders WHERE orders_id = " . (int)$order_id);
    }

  }
