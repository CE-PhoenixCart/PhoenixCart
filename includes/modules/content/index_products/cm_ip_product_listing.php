<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_ip_product_listing extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_IP_PRODUCT_LISTING_';

    function __construct() {
      parent::__construct(__FILE__);
    }

    function execute() {
      global $current_category_id;

      $listing_sql = sprintf(<<<'EOSQL'
SELECT m.*, %s
 FROM
  products p
    LEFT JOIN specials s ON p.products_id = s.products_id
    INNER JOIN products_description pd ON p.products_id = pd.products_id
    LEFT JOIN (SELECT products_id, COUNT(*) AS attribute_count FROM products_attributes GROUP BY products_id) a ON p.products_id = a.products_id
EOSQL
      , Product::COLUMNS);

// show the products of a specified manufacturer
      if (empty($_GET['manufacturers_id'])) {
// show the products in a given category
        if (isset($_GET['filter_id']) && !Text::is_empty($_GET['filter_id'])) {
// We are asked to show only a specific manufacturer
          $listing_sql .= sprintf(<<<'EOSQL'
    INNER JOIN manufacturers m ON p.manufacturers_id = m.manufacturers_id
    INNER JOIN products_to_categories p2c ON p.products_id = p2c.products_id
 WHERE p.products_status = 1 AND m.manufacturers_id = %d AND pd.language_id = %d AND p2c.categories_id = %d
EOSQL
          , (int)$_GET['filter_id'], (int)$_SESSION['languages_id'], (int)$current_category_id);
        } else {
// We show them all
          $listing_sql .= sprintf(<<<'EOSQL'
    INNER JOIN products_to_categories p2c ON p.products_id = p2c.products_id
    LEFT JOIN manufacturers m ON p.manufacturers_id = m.manufacturers_id
  WHERE p.products_status = 1 AND pd.language_id = %d AND p2c.categories_id = %d
EOSQL
          , (int)$_SESSION['languages_id'], (int)$current_category_id);
        }
      } else {
        if (isset($_GET['filter_id']) && !Text::is_empty($_GET['filter_id'])) {
// We are asked to show only a specific category
          $listing_sql .= sprintf(<<<'EOSQL'
    INNER JOIN manufacturers m ON p.manufacturers_id = m.manufacturers_id
    INNER JOIN products_to_categories p2c ON p.products_id = p2c.products_id
  WHERE p.products_status = 1 AND m.manufacturers_id = %d AND pd.language_id = %d AND p2c.categories_id = %d
EOSQL
          , (int)$_GET['manufacturers_id'], (int)$_SESSION['languages_id'], (int)$_GET['filter_id']);
        } else {
// We show them all
          $listing_sql .= sprintf(<<<'EOSQL'
    INNER JOIN manufacturers m ON p.manufacturers_id = m.manufacturers_id
  WHERE p.products_status = 1 AND pd.language_id = %d AND m.manufacturers_id = %d
EOSQL
          , (int)$_SESSION['languages_id'], (int)$_GET['manufacturers_id']);
        }
      }

      require 'includes/system/segments/sortable_product_columns.php';

// optional Product List Filter
      $output = [];
      if (PRODUCT_LIST_FILTER > 0) {
        if (empty($_GET['manufacturers_id'])) {
          $filterlist_sql = sprintf(<<<'EOSQL'
SELECT DISTINCT m.manufacturers_id AS id, m.manufacturers_name AS text
 FROM products p, products_to_categories p2c, manufacturers m
 WHERE p.products_status = 1
   AND p.manufacturers_id = m.manufacturers_id
   AND p.products_id = p2c.products_id
   AND p2c.categories_id = %d
 ORDER BY m.manufacturers_name
EOSQL
          , (int)$current_category_id);
        } else {
          $filterlist_sql = sprintf(<<<'EOSQL'
SELECT DISTINCT c.categories_id AS id, cd.categories_name AS text
 FROM products p, products_to_categories p2c, categories c, categories_description cd
 WHERE p.products_status = 1
   AND p.products_id = p2c.products_id
   AND p2c.categories_id = c.categories_id
   AND p2c.categories_id = cd.categories_id
   AND cd.language_id = %d AND p.manufacturers_id = %d
 ORDER BY cd.categories_name
EOSQL
          , (int)$_SESSION['languages_id'], (int)$_GET['manufacturers_id']);
        }

        $options = $GLOBALS['db']->fetch_all($filterlist_sql);
        if (count($options) > 1) {
          $form = new Form('filter', $GLOBALS['Linker']->build('index.php', [], false), 'get');

          if (empty($_GET['manufacturers_id'])) {
            $form->hide('cPath', $GLOBALS['cPath']);
            $options = array_merge([['id' => '', 'text' => TEXT_ALL_MANUFACTURERS]], $options);
          } else {
            $form->hide('manufacturers_id', $_GET['manufacturers_id']);
            $options = array_merge([['id' => '', 'text' => TEXT_ALL_CATEGORIES]], $options);
          }

          $form->hide('sort', $_GET['sort']);

          $select = new Select('filter_id', $options, ['onchange' => 'this.form.submit()']);
          if (isset($_GET['filter_id'])) {
            $select->set_selection($_GET['filter_id']);
          }
          $form->hide_session_id();
          $output = [$form, $select, '</form>'];
        }
      }

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_IP_PRODUCT_LISTING_STATUS' => [
          'title' => 'Enable Product Listing Module',
          'value' => 'True',
          'desc' => 'Should this module be enabled?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_IP_PRODUCT_LISTING_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_IP_PRODUCT_LISTING_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
