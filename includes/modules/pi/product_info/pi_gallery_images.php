<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class pi_gallery_images extends abstract_module  {

    const CONFIG_KEY_BASE = 'PI_GALLERY_IMAGES_';

    public $group;

    public function __construct() {
      parent::__construct();
      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_pi_modular::display_layout() . '</div>';

      if ( defined('PI_GALLERY_IMAGES_STATUS') ) {
        $this->group = 'pi_modules_' . strtolower(PI_GALLERY_IMAGES_GROUP);
      }
    }

    public function getOutput() {
      $other_images = $GLOBALS['db']->fetch_all("SELECT image, htmlcontent FROM products_images WHERE products_id = '" . (int)$GLOBALS['product']->get('id') . "' ORDER BY sort_order");

      if (count($other_images) > 0) {
        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/block_template.php';
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
        $this->config_key_base . 'GROUP' => [
          'title' => 'Module Display',
          'value' => 'B',
          'desc' => 'Where should this module display on the product info page?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-12',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'CONTENT_WIDTH_EACH' => [
          'title' => 'Thumbnail Container',
          'value' => 'col-4 col-sm-6 col-lg-4 mb-1',
          'desc' => 'What container should each thumbnail be shown in?',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '210',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
