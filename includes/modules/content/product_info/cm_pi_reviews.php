<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_reviews extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      $review_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT rd.*, r.*, p.*, pd.*
 FROM reviews r
   INNER JOIN reviews_description rd ON r.reviews_id = rd.reviews_id
   INNER JOIN products p ON r.products_id = p.products_id
   INNER JOIN products_description pd ON p.products_id = pd.products_id AND rd.languages_id = pd.language_id
 WHERE p.products_status = 1 AND r.reviews_status = 1 AND r.products_id = %d AND rd.languages_id = %d
 ORDER BY r.%s DESC
 LIMIT %d
EOSQL
        , (int)$_GET['products_id'],
        (int)$_SESSION['languages_id'],
        MODULE_CONTENT_PRODUCT_INFO_REVIEWS_ORDER,
        (int)MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_LIMIT));

      if (mysqli_num_rows($review_query) > 0) {
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
          'value' => 'col-sm-6',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'CONTENT_WIDTH_EACH' => [
          'title' => 'Review Container',
          'value' => 'col-sm-6',
          'desc' => 'What container should each Review be shown in?',
        ],
        $this->config_key_base . 'CONTENT_LIMIT' => [
          'title' => 'Number of Reviews',
          'value' => '99',
          'desc' => 'How many reviews should be shown?',
        ],
        $this->config_key_base . 'ORDER' => [
          'title' => 'Sort Order',
          'value' => 'reviews_rating',
          'desc' => 'Display Reviews by Rating (High to Low) or Date Added (New to Old)',
          'set_func' => "Config::select_one(['reviews_rating', 'date_added'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }

