<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_i_upcoming_products extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_UPCOMING_PRODUCTS_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      $sort_field = $GLOBALS['db']->escape(MODULE_CONTENT_UPCOMING_PRODUCTS_EXPECTED_FIELD);
      if ('ASC' !== strtoupper(MODULE_CONTENT_UPCOMING_PRODUCTS_EXPECTED_SORT)) {
        $sort_field .= ' DESC';
      }

      $expected_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT p.products_id, pd.products_name, products_date_available AS date_expected
 FROM products p INNER JOIN products_description pd ON p.products_id = pd.products_id
 WHERE TO_DAYS(products_date_available) >= TO_DAYS(NOW()) AND pd.language_id = %d
 ORDER BY %s
 LIMIT %d
EOSQL
        , (int)$_SESSION['languages_id'],
        $sort_field,
        (int)MODULE_CONTENT_UPCOMING_PRODUCTS_MAX_DISPLAY));

      if (mysqli_num_rows($expected_query) > 0) {
        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_UPCOMING_PRODUCTS_STATUS' => [
          'title' => 'Enable Upcoming Products Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_UPCOMING_PRODUCTS_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in? (12 = full width, 6 = half width).',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_UPCOMING_PRODUCTS_MAX_DISPLAY' => [
          'title' => 'Maximum Display',
          'value' => '6',
          'desc' => 'Maximum Number of products that should show in this module?',
        ],
        'MODULE_CONTENT_UPCOMING_PRODUCTS_EXPECTED_SORT' => [
          'title' => 'Sort Order',
          'value' => 'DESC',
          'desc' => 'This is the sort order used in the output.',
          'set_func' => "Config::select_one(['ASC', 'DESC'], ",
        ],
        'MODULE_CONTENT_UPCOMING_PRODUCTS_EXPECTED_FIELD' => [
          'title' => 'Sort Field',
          'value' => 'date_expected',
          'desc' => 'The column to sort by in the output.',
          'set_func' => "Config::select_one(['products_name', 'date_expected'], ",
        ],
        'MODULE_CONTENT_UPCOMING_PRODUCTS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '400',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
