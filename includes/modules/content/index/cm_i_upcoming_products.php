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
 WHERE products_date_available >= NOW() AND pd.language_id = %d
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
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12 mb-4',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'MAX_DISPLAY' => [
          'title' => 'Maximum Display',
          'value' => '6',
          'desc' => 'Maximum Number of products that should show in this module?',
        ],
        $this->config_key_base . 'EXPECTED_SORT' => [
          'title' => 'Sort Order',
          'value' => 'DESC',
          'desc' => 'This is the sort order used in the output.',
          'set_func' => "Config::select_one(['ASC', 'DESC'], ",
        ],
        $this->config_key_base . 'EXPECTED_FIELD' => [
          'title' => 'Sort Field',
          'value' => 'date_expected',
          'desc' => 'The column to sort by in the output.',
          'set_func' => "Config::select_one(['products_name', 'date_expected'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '400',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
