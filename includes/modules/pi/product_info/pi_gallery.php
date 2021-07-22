<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class pi_gallery extends abstract_module  {

    const CONFIG_KEY_BASE = 'PI_GALLERY_';

    public $content_width;
    public $api_version;
    public $group;

    public function __construct() {
      parent::__construct();
      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . $this->display_layout() . '</div>';

      if ( defined('PI_GALLERY_STATUS') ) {
        $this->group = 'pi_modules_' . strtolower(PI_GALLERY_GROUP);
        $this->content_width = (int)PI_GALLERY_CONTENT_WIDTH;
      }
    }

    public function getOutput() {
      global $product;

      $content_width = $this->content_width;
      $thumbnail_width = PI_GALLERY_CONTENT_WIDTH_EACH;

      $pi_image = $pi_thumb = '';

      if (Text::is_empty($product->get('image'))) {
        return;
      }

      $album_name = sprintf(PI_GALLERY_ALBUM_NAME, $product->get('name'));
      $album_exit = PI_GALLERY_ALBUM_CLOSE;

      $pi_html = [];
      $pi_html[0] = ['image' => $product->get('image'), 'htmlcontent' => $product->get('name')];

      $pi_query = $GLOBALS['db']->query("SELECT image, htmlcontent FROM products_images WHERE products_id = " . (int)$product->get('id') . " ORDER BY sort_order");
      $pi_total = mysqli_num_rows($pi_query);

      if ($pi_total > 0) {
        $pi_counter = 1;

        while ($pi = $pi_query->fetch_assoc()) {
          $pi_html[$pi_counter] = $pi;

          $pi_counter++;
        }
      }

      $active_image = array_shift($pi_html);
      $other_images = $pi_html;

      $modal_size = PI_GALLERY_MODAL_SIZE;

      $tpl_data = ['group' => $this->group, 'file' => __FILE__];
      include 'includes/modules/block_template.php';
    }

    public function display_layout() {
      return cm_pi_modular::display_layout();
    }

    protected function get_parameters() {
      return [
        'PI_GALLERY_STATUS' => [
          'title' => 'Enable Gallery Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the product info page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'PI_GALLERY_GROUP' => [
          'title' => 'Module Display',
          'value' => 'B',
          'desc' => 'Where should this module display on the product info page?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        'PI_GALLERY_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'PI_GALLERY_CONTENT_WIDTH_EACH' => [
          'title' => 'Thumbnail Width',
          'value' => 'col-4 col-sm-6 col-lg-4',
          'desc' => 'What width container should each thumbnail be shown in? Default:  XS 3 each row, SM/MD 2 each row, LG/XL 3 each row.',
        ],
        'PI_GALLERY_MODAL_SIZE' => [
          'title' => 'Modal Popup Size',
          'value' => 'modal-md',
          'desc' => 'Choose the size of the Popup.  sm = small, md = medium etc.',
          'set_func' => "Config::select_one(['modal-sm', 'modal-md', 'modal-lg', 'modal-xl'], ",
        ],
        'PI_GALLERY_SWIPE_ARROWS' => [
          'title' => 'Show Swipe Arrows',
          'value' => 'True',
          'desc' => 'Swipe Arrows make for a better User Experience in some cases.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'PI_GALLERY_INDICATORS' => [
          'title' => 'Show Indicators',
          'value' => 'True',
          'desc' => 'Indicators allow users to jump from image to image without having to swipe.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'PI_GALLERY_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '200',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
