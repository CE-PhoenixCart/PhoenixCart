<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_review_stars extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PI_REVIEW_STARS_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $product;

      $review_link = $GLOBALS['Linker']->build(
        'ext/modules/content/reviews/write.php',
        ['products_id' => (int)$product->get('id')]);

      $review_ratings = [];
      $review_count = count($product->get('reviews'));
      if ($review_count > 0) {
        $review_ratings[] = new star_rating((float)(int)$product->get('review_rating'));

        if (1 === (int)$review_count) {
          $review_ratings[] = sprintf(MODULE_CONTENT_PI_REVIEW_STARS_COUNT_ONE, (int)$review_count);
        } else {
          $review_ratings[] = sprintf(MODULE_CONTENT_PI_REVIEW_STARS_COUNT, (int)$review_count);
        }

        $do_review = MODULE_CONTENT_PI_REVIEW_STARS_DO_REVIEW;
      } else {
        $review_ratings[] = sprintf(MODULE_CONTENT_PI_REVIEW_STARS_COUNT, 0);

        $do_review = MODULE_CONTENT_PI_REVIEW_STARS_DO_FIRST_REVIEW;
      }

      $tpl_data = ['group' => $this->group, 'file' => __FILE__];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_PI_REVIEW_STARS_STATUS' => [
          'title' => 'Enable Review Stars/Link Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the product info page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_PI_REVIEW_STARS_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_PI_REVIEW_STARS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '55',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
