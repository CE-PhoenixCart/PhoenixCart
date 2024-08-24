<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class Outgoing_winback {
    const INTERVAL = 'P90D';

    public static function execute() {
      if ('checkout_success.php' === basename(Request::get_page())) {
        if (isset($_SESSION['customer_id'])) {
          $customer = new customer($_SESSION['customer_id']);

          $_data_array = ['customer_id'   => (int)$_SESSION['customer_id'],
                          'fname'         => Text::prepare($customer->get('firstname')),
                          'lname'         => Text::prepare($customer->get('lastname')),
                          'email_address' => Text::prepare($customer->get('email_address')),
                          'date_added'    => 'now()'];

          $_data_array['slug'] = basename(__FILE__, '.php');

          $winback = new DateTime();
          $winback->add(new DateInterval(self::INTERVAL));

          $send_at_date = $winback->format('Y-m-d H:i:s');
          $_data_array['send_at'] = Text::prepare($send_at_date);

// extra merge tags for this module
          $ordered = new DateTime();
          
          $_mt['order_date']  = Text::input($ordered->format('Y-m-d H:i:s'));
          $_mt['order_day']   = Text::input($ordered->format('jS'));
          $_mt['order_month'] = Text::input($ordered->format('F'));
          $_mt['order_year']  = Text::input($ordered->format('Y'));

          $_data_array['merge_tags'] = json_encode($_mt, JSON_PRETTY_PRINT);

          $GLOBALS['db']->perform('outgoing', $_data_array);
        }
      }
    }

    public static function remove() {
      if ('checkout_success.php' === basename(Request::get_page())) {
        $GLOBALS['db']->query("delete from outgoing where customer_id = '" . (int)$_SESSION['customer_id'] . "' and slug = 'winback'");
      }
    }

    public static function pages() {
      global $display_pages;

      $display_pages[] = 'checkout_success.php';

      return $display_pages;
    }

    public static function merge_tags() {
      global $merge_tags;

      $f = basename(__FILE__, '.php');

      $merge_tags[$f]['{{ORDER_DATE}}']  = 'Order Date';
      $merge_tags[$f]['{{ORDER_DAY}}']   = 'Day (eg 20th)';
      $merge_tags[$f]['{{ORDER_MONTH}}'] = 'Month (eg January)';
      $merge_tags[$f]['{{ORDER_YEAR}}']  = 'Year (eg 2024)';

      return $merge_tags;
    }

    public static function admin_add($customer_id, $send_at) {
      $customer = new customer($customer_id);

      $_data_array = ['customer_id'   => (int)$customer_id,
                      'fname'         => Text::prepare($customer->get('firstname')),
                      'lname'         => Text::prepare($customer->get('lastname')),
                      'email_address' => Text::prepare($customer->get('email_address')),
                      'date_added'    => 'now()'];

      $_data_array['slug'] = basename(__FILE__, '.php');

      // get the last order data
      // of this customer
      $order_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT orders_id, date_purchased
 FROM orders
 WHERE customers_id = %d
 ORDER BY orders_id DESC
 LIMIT 1
EOSQL
          , (int)$customer_id));

      if (mysqli_num_rows($order_query)) {
        $order = $order_query->fetch_assoc();

        $winback = new DateTime($order['date_purchased']);
        $winback->add(new DateInterval(self::INTERVAL));

        $send_at_date = $winback->format('Y-m-d H:i:s');
        $_data_array['send_at'] = Text::prepare($send_at_date);

        $order_id = $order['orders_id'];
        $identifier = ["order:$order_id"];

        $_data_array['identifier'] = implode(',', $identifier);

        $ordered = new DateTime($order['date_purchased']);
        $_mt['order_date']  = Text::input($ordered->format('Y-m-d H:i:s'));
        $_mt['order_day']   = Text::input($ordered->format('jS'));
        $_mt['order_month'] = Text::input($ordered->format('F'));
        $_mt['order_year']  = Text::input($ordered->format('Y'));

        $_data_array['merge_tags'] = json_encode($_mt, JSON_PRETTY_PRINT);

        $GLOBALS['db']->perform('outgoing', $_data_array);
      }
    }

    public static function system_add() {
    }
    
    public static function email() {
      $s = basename(__FILE__, '.php');
      
      return ['id' => $s, 'text' => $s];
    }
    
    public static function dropdown() {
      $s = basename(__FILE__, '.php');
      
      return ['id' => $s, 'text' => $s];
    }

  }
