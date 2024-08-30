<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class Outgoing_order_thanks {
    const INTERVAL = 'PT4H';
    
    public static function execute() {
      if ('checkout_success.php' === basename(Request::get_page())) {
        $customer = new customer($_SESSION['customer_id']);
        $order_id = (int)$GLOBALS['order_id']; 
        
        $thanks = new order($order_id);
        
        $identifier = ["order:$order_id"];

        $_data_array = ['customer_id'   => (int)$_SESSION['customer_id'],
                        'identifier'    => implode(',', $identifier),
                        'fname'         => Text::prepare($customer->get('firstname')),
                        'lname'         => Text::prepare($customer->get('lastname')),
                        'email_address' => Text::prepare($customer->get('email_address')),
                        'date_added'    => 'now()'];

        $_data_array['slug'] = basename(__FILE__, '.php');

        $ot = new DateTime(); 
        $ot->add(new DateInterval(self::INTERVAL));

        $send_at_date = $ot->format('Y-m-d H:i:s');
        $_data_array['send_at'] = Text::prepare($send_at_date);

// extra merge tags for this module
        $ordered = new DateTime();
        
        $_mt['order_date']  = Text::input($ordered->format('Y-m-d H:i:s'));
        $_mt['order_day']   = Text::input($ordered->format('jS'));
        $_mt['order_month'] = Text::input($ordered->format('F'));
        $_mt['order_year']  = Text::input($ordered->format('Y'));
        
        // make the list of products
        $list = '';
        foreach ($thanks->products as $product) {
          $list .= $product['name'] . PHP_EOL;
        }

        $_mt['order_products'] = Text::prepare($list);
        $_mt['order_id'] = Text::input($order_id);

        $_data_array['merge_tags'] = json_encode($_mt, JSON_PRETTY_PRINT);

        $GLOBALS['db']->perform('outgoing', $_data_array);
      }
    }

    public static function remove() {
      // should a request be deleted?
      // feedback needed
      return null;
    }

    public static function pages() {
      global $display_pages;

      $display_pages[] = 'checkout_success.php';

      return $display_pages;
    }

    public static function merge_tags() {
      global $merge_tags;

      $f = basename(__FILE__, '.php');
      
      $merge_tags[$f]['{{ORDER_PRODUCTS}}']  = 'Ordered Products';
      $merge_tags[$f]['{{ORDER_ID}}']  = 'Order ID';
      $merge_tags[$f]['{{ORDER_DATE}}']  = 'Order Date';
      $merge_tags[$f]['{{ORDER_DAY}}']   = 'Day (eg 20th)';
      $merge_tags[$f]['{{ORDER_MONTH}}'] = 'Month (eg January)';
      $merge_tags[$f]['{{ORDER_YEAR}}']  = 'Year (eg 2024)';
      
      return $merge_tags;
    }

    public static function admin_add() {
    }

    public static function system_add() {
    }
    
    public static function email() {
    }
    
    public static function dropdown() {
      $s = basename(__FILE__, '.php');
      
      return ['id' => $s, 'text' => $s];
    }

  }
  