<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_also_purchased extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PRODUCT_INFO_ALSO_PURCHASED_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      $also_purchased_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT %s
 FROM orders_products opa
   INNER JOIN orders_products opb ON opa.orders_id = opb.orders_id AND opa.products_id != opb.products_id
   INNER JOIN orders o ON opb.orders_id = o.orders_id
   INNER JOIN products p ON opb.products_id = p.products_id
   INNER JOIN products_description pd ON p.products_id = pd.products_id
   LEFT JOIN specials s ON p.products_id = s.products_id
   LEFT JOIN (SELECT products_id, COUNT(*) AS attribute_count FROM products_attributes GROUP BY products_id) a ON p.products_id = a.products_id
 WHERE p.products_status = 1
   AND opa.products_id = %d
   AND pd.language_id = %d
 GROUP BY p.products_id
 ORDER BY o.date_purchased DESC
 LIMIT %d
EOSQL
        , Product::COLUMNS,
        (int)$_GET['products_id'],
        (int)$_SESSION['languages_id'],
        (int)$this->base_constant('CONTENT_LIMIT')));

      if (mysqli_num_rows($also_purchased_query) > 0) {
        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_PRODUCT_INFO_ALSO_PURCHASED_STATUS' => [
          'title' => 'Enable Also Purchased Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the product info page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_PRODUCT_INFO_ALSO_PURCHASED_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_PRODUCT_INFO_ALSO_PURCHASED_CONTENT_LIMIT' => [
          'title' => 'Number of Products',
          'value' => '4',
          'desc' => 'How many products (maximum) should be shown?',
        ],
        'MODULE_CONTENT_PRODUCT_INFO_ALSO_PURCHASED_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '120',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
