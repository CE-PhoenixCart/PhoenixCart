<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  class pi_review_stars extends abstract_module {

    const CONFIG_KEY_BASE = 'PI_REVIEW_STARS_';

    public $group = 'pi_modules_c';

    public function __construct() {
      parent::__construct();

      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_pi_modular::display_layout() . '</div>';

      if ( $this->enabled ) {
        $this->group = 'pi_modules_' . strtolower(PI_REVIEW_STARS_GROUP);
      }
    }

    public function getOutput() {
      $review_link = $GLOBALS['Linker']->build('ext/modules/content/reviews/write.php', ['products_id' => (int)$GLOBALS['product']->get('id')]);

      $review_ratings = [];
      $review_count = count($GLOBALS['product']->get('reviews'));
      
      if ($review_count > 0) {
        $review_ratings[] = new star_rating((float)(int)$GLOBALS['product']->get('review_rating'));

        if (1 === (int)$review_count) {
          $review_ratings[] = sprintf(PI_REVIEW_STARS_COUNT_ONE, (int)$review_count);
        } else {
          $review_ratings[] = sprintf(PI_REVIEW_STARS_COUNT, (int)$review_count);
        }

        $do_review = PI_REVIEW_STARS_DO_REVIEW;
      } else {
        $review_ratings[] = sprintf(PI_REVIEW_STARS_COUNT, 0);

        $do_review = PI_REVIEW_STARS_DO_FIRST_REVIEW;
      }
      
      $tpl_data = ['group' => $this->group, 'file' => __FILE__];
      include 'includes/modules/block_template.php';
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'GROUP' => [
          'title' => 'Module Display',
          'value' => 'C',
          'desc' => 'Where should this module display on the product info page?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12 mb-2',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '55',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
