<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_cs_product_notifications extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function process() {
      if ( isset($_SESSION['customer_id'], $_GET['action'])
        && ('update' === $_GET['action'])
        && !empty($_POST['notify'])
        && is_array($_POST['notify']) )
      {
        $global_query = $GLOBALS['db']->query("SELECT global_product_notifications FROM customers_info WHERE customers_info_id = " . (int)$_SESSION['customer_id']);
        $global = $global_query->fetch_assoc();

        if ( '1' === $global['global_product_notifications'] ) {
          return;
        }

        foreach ( array_unique($_POST['notify']) as $n ) {
          if ( is_numeric($n) && ($n > 0) ) {
            $check_query = $GLOBALS['db']->query("SELECT products_id FROM products_notifications WHERE products_id = " . (int)$n . " AND customers_id = " . (int)$_SESSION['customer_id'] . " LIMIT 1");

            if ( !mysqli_num_rows($check_query) ) {
              $GLOBALS['db']->query("INSERT INTO products_notifications (products_id, customers_id, date_added) VALUES ('" . (int)$n . "', '" . (int)$_SESSION['customer_id'] . "', NOW())");
            }
          }
        }
      }
    }

    public function execute() {
      if ( isset($_SESSION['customer_id']) ) {
        $global_query = $GLOBALS['db']->query("SELECT global_product_notifications FROM customers_info WHERE customers_info_id = " . (int)$_SESSION['customer_id']);
        $global = $global_query->fetch_assoc();

        if ( $global['global_product_notifications'] != '1' ) {
          $products_displayed = [];

          $products_query = $GLOBALS['db']->query("SELECT DISTINCT products_id, products_name FROM orders_products WHERE orders_id = " . (int)$GLOBALS['order_id'] . " ORDER BY products_name");
          while ($products = $products_query->fetch_assoc()) {
            if ( !isset($products_displayed[$products['products_id']]) ) {
              $products_displayed[$products['products_id']] = $products['products_name'];
            }
          }

          $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
          include 'includes/modules/content/cm_template.php';
        }
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-5',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '1000',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

    public function install($parameter_key = null) {
      parent::install($parameter_key);

      $GLOBALS['db']->query(<<<'EOSQL'
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method)
 VALUES ('shop', 'checkout_success', 'injectAppTop', 'notify', 'cm_cs_product_notifications', 'process')
EOSQL
        );
    }

    public function remove() {
      parent::remove();

      $GLOBALS['db']->query("DELETE FROM hooks WHERE hooks_class = 'cm_cs_product_notifications'");
    }

  }
