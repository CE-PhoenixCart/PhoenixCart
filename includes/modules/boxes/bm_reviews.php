<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class bm_reviews extends abstract_block_module {

    const CONFIG_KEY_BASE = 'MODULE_BOXES_REVIEWS_';

    public function execute() {
      if ($product = random_review::build()) {
        $card = [
          'extra' => new star_rating((float)$product->get('reviews_rating')) . '<br>'
                   . htmlspecialchars($product->get('reviews_text')) . '...',
        ];

        $box = [
          'parameters' => ['product_card.php', 'component'],
          'classes' => 'is-product bm-reviews',
          'title' => MODULE_BOXES_REVIEWS_BOX_TITLE,
          'attributes' => $product->get('data_attributes'),
        ];

        $tpl_data = [
          'group' => $this->group,
          'file' => 'box.php',
          'type' => 'component',
        ];
        include 'includes/modules/block_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_BOXES_REVIEWS_STATUS' => [
          'title' => 'Enable Reviews Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_BOXES_REVIEWS_CONTENT_PLACEMENT' => [
          'title' => 'Content Placement',
          'value' => 'Right Column',
          'desc' => 'Should the module be loaded in the left or right column?',
          'set_func' => "Config::select_one(['Left Column', 'Right Column'], ",
        ],
        'MODULE_BOXES_REVIEWS_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
