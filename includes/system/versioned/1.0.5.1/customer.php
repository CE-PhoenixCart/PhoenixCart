<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class customer {

    private $id;
    private $data = [];
    private $unpersisted = [];

    /**
     * @param string $customer_id
     */
    public function __construct(string $customer_id = '') {
      $this->id = $customer_id;
    }

    public function preload_columns(&$to) {
      $customer_data = Guarantor::ensure_global('customer_data');

      $customer_data->get('state', $to);
      $customer_data->get('zone_id', $to);
      $customer_data->get('country', $to);
      $customer_data->get('name', $to);
    }

    /**
     * @param int $to The ID of the customer's address.  Or 0 to use the customers table.
     * @return array Of the address information.
     */
    protected function fetch_address(int $to = 0) {
      if (isset($this->data[$to])) {
        return;
      }

      $customer_data = Guarantor::ensure_global('customer_data');

      if ($to > 0) {
        $address_query = $GLOBALS['db']->query($customer_data->build_read(['id', 'address'], 'address_book', ['id' => (int)$this->id, 'address_book_id' => (int)$to]));
      } else {
        $address_query = $GLOBALS['db']->query($customer_data->build_read($customer_data->list_all_capabilities(), 'both', ['id' => (int)$this->id]));
      }

      $this->data[$to] = array_filter($address_query->fetch_assoc(), function ($v) { return !Text::is_empty($v); });
      if (!is_null($this->data[$to])) {
        $this->preload_columns($this->data[$to]);
      }
    }

    /**
     * @param int $to The ID of the customer's address.
     * @return array Of the address information.
     */
    public function &fetch_to_address($to = null) {
      if (!empty($to) && is_array($to)) {
        if (empty($to['state'])) {
          $to['state'] = $to['zone_name'] ?? null;
        }

        if (!isset($to['country_id'])) {
          $to['country_id'] = $to['country']['id'] ?? null;
        }

        if (!isset($to['id'])) {
          $to['id'] = $this->id;
        }

        $this->preload_columns($to);
        return $to;
      } elseif (is_numeric($to ?? null)) {
        $this->fetch_address($to);
      } else {
        if (!isset($this->data[0])) {
          $customer_data = Guarantor::ensure_global('customer_data');
          $this->data[0] = array_fill_keys($customer_data->list_all_capabilities(), null);
          $customer_data->get('country', $this->data[0]);
        }
        $to = 0;
      }

      return $this->data[$to];
    }

    public function get_id() {
      return $this->id;
    }

    public function get($key, $to = 0) {
      $this->fetch_to_address($to);
      if (!isset($this->data[$to][$key])) {
        Guarantor::ensure_global('customer_data')->get($key, $this->data[$to]);
      }

      return $this->data[$to][$key] ?? null;
    }

    public function set($key, $value, $to = 0) {
      $customer_details = $this->fetch_to_address($to);
      if (!isset($customer_details[$key])) {
        Guarantor::ensure_global('customer_data')->get($key, $customer_details);
      }

      if (!isset($customer_details[$key]) || $customer_details[$key] !== $value) {
        $this->unpersisted[$key] = $value;
        $this->data[$to][$key] = $value;
      }
    }

    public function persist($to = 0) {
      if ($to > 0) {
        Guarantor::ensure_global('customer_data')->update(
          $this->unpersisted,
          ['id' => $this->id, 'address_book_id' => (int)$to],
          'address_book');
      } else {
        Guarantor::ensure_global('customer_data')->update(
          $this->unpersisted,
          ['id' => $this->id, 'address_book_id' => (int)$this->data[0]['default_address_id']],
          'both');
      }

      $this->unpersisted = [];
    }

    /**
     * @return string A short version of the customer's name.
     */
    public function get_short_name() {
      return $this->get('short_name');
    }

    public function get_default_address_id() {
      return $this->get('default_address_id');
    }

    public function get_country_id() {
      return $this->get('country_id');
    }

    public function get_zone_id() {
      return $this->get('zone_id');
    }

    public function get_all_addresses_query() {
      return $GLOBALS['db']->query(Guarantor::ensure_global('customer_data')->build_read(['address'], 'address_book', ['id' => (int)$this->id]));
    }

    public function get_all_addresses() {
      $addresses_query = $this->get_all_addresses_query();
      while ($address = $addresses_query->fetch_assoc()) {
        yield $address;
      }
    }

    public function count_addresses() {
      return mysqli_num_rows($this->get_all_addresses_query());
    }

    public function make_address_label($to = 0, $html = false, $boln = '', $eoln = "\n") {
      return Guarantor::ensure_global('customer_data')->get_module('address')->format($this->fetch_to_address($to), $html, $boln, $eoln);
    }

    public function count_orders() {
      return $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT COUNT(*) AS total
 FROM orders o INNER JOIN orders_status s ON o.orders_status = s.orders_status_id
 WHERE o.customers_id = %d AND s.language_id = %d AND s.public_flag = 1
EOSQL
        , (int)$this->id, (int)$_SESSION['languages_id']))->fetch_assoc()['total'];
    }

  }
