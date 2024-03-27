<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_gallery extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PI_GALLERY_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      if (!Text::is_empty($GLOBALS['product']->get('image'))) {
        $active_image = ['image' => $GLOBALS['product']->get('image'), 'htmlcontent' => $GLOBALS['product']->get('name')];
        $other_images = $GLOBALS['db']->fetch_all("SELECT image, htmlcontent FROM products_images WHERE products_id = '" . (int)$GLOBALS['product']->get('id') . "' ORDER BY sort_order");

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
          'value' => 'col-sm-4',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'CONTENT_WIDTH_EACH' => [
          'title' => 'Thumbnail Container',
          'value' => 'col-4 col-sm-6 col-lg-4',
          'desc' => 'What container should each thumbnail be shown in? Default:  XS 3 each row, SM/MD 2 each row, LG/XL 3 each row.',
        ],
        $this->config_key_base . 'MODAL_SIZE' => [
          'title' => 'Modal Popup Size',
          'value' => 'modal-md',
          'desc' => 'Choose the size of the Popup.  sm = small, md = medium etc.',
          'set_func' => "Config::select_one(['modal-sm', 'modal-md', 'modal-lg', 'modal-xl'], ",
        ],
        $this->config_key_base . 'SWIPE_ARROWS' => [
          'title' => 'Show Swipe Arrows',
          'value' => 'True',
          'desc' => 'Swipe Arrows make for a better User Experience in some cases.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'INDICATORS' => [
          'title' => 'Show Indicators',
          'value' => 'True',
          'desc' => 'Indicators allow users to jump from image to image without having to swipe.',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '65',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }
  }
