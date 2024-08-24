<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class Outgoing_no_checkout {
    const INTERVAL = 'P10D';

    public static function execute() {
      if ('create_account_success.php' === basename(Request::get_page())) {
        if (isset($_SESSION['customer_id'])) {
          $customer = new customer($_SESSION['customer_id']);

          $_data_array = ['customer_id'   => (int)$_SESSION['customer_id'],
                          'fname'         => Text::prepare($customer->get('firstname')),
                          'lname'         => Text::prepare($customer->get('lastname')),
                          'email_address' => Text::prepare($customer->get('email_address')),
                          'date_added'    => 'now()'];

          $_data_array['slug'] = basename(__FILE__, '.php');

          $no_checkout = new DateTime();
          $no_checkout->add(new DateInterval(self::INTERVAL));

          $send_at_date = $no_checkout->format('Y-m-d H:i:s');
          $_data_array['send_at'] = Text::prepare($send_at_date);

// extra merge tags for this module
          $joined = new DateTime($customer->get('date_account_created'));
          $_mt['sign_up_date']  = Text::prepare(Date::expound($customer->get('date_account_created')));
          $_mt['sign_up_day']   = Text::input($joined->format('jS'));
          $_mt['sign_up_month'] = Text::input($joined->format('F'));

          $_data_array['merge_tags'] = json_encode($_mt, JSON_PRETTY_PRINT);

          $GLOBALS['db']->perform('outgoing', $_data_array);
        }
      }
    }

    public static function remove() {
      if ('checkout_success.php' === basename(Request::get_page())) {
        $GLOBALS['db']->query("DELETE FROM outgoing WHERE customer_id = '" . (int)$_SESSION['customer_id'] . "' AND slug = 'no_checkout'");
      }
    }

    public static function pages() {
      global $display_pages;

      $display_pages[] = 'create_account_success.php';

      return $display_pages;
    }

    public static function merge_tags() {
      global $merge_tags;

      $f = basename(__FILE__, '.php');

      $merge_tags[$f]['{{SIGN_UP_DATE}}'] = 'Date of Account Creation';
      $merge_tags[$f]['{{SIGN_UP_DAY}}']   = 'Day (eg 20th)';
      $merge_tags[$f]['{{SIGN_UP_MONTH}}'] = 'Month (eg January)';

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

      $_data_array['send_at'] = Text::prepare($send_at);

  // extra merge tags for this module
      $joined = new DateTime($customer->get('date_account_created'));
      $_mt['sign_up_date']  = Text::prepare(Date::expound($customer->get('date_account_created')));
      $_mt['sign_up_day']   = Text::input($joined->format('jS'));
      $_mt['sign_up_month'] = Text::input($joined->format('F'));

      $_data_array['merge_tags'] = json_encode($_mt, JSON_PRETTY_PRINT);

      $GLOBALS['db']->perform('outgoing', $_data_array);
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
