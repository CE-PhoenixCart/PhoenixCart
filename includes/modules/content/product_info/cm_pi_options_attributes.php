<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_pi_options_attributes extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_PI_OA_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      $options = $this->build_options();
      if (count($options)) {
        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
        include 'includes/modules/content/cm_template.php';
      }
    }

    public function build_options() {
      global $currencies, $product;

      $attributes = $product->get('attributes');
      $options = [];
      foreach ($attributes as $option_id => $attribute) {
        $option_choices = [];

        if ((MODULE_CONTENT_PI_OA_ENFORCE === 'True') && (MODULE_CONTENT_PI_OA_HELPER === 'True')) {
          $option_choices[] = ['id' => '', 'text' => MODULE_CONTENT_PI_OA_ENFORCE_SELECTION];
        }

        foreach ($attribute['values'] as $value_id => $value) {
          $text = $value['name'];
          if ($value['price'] != '0') {
            $text .= ' (' . $value['prefix']
                   . $currencies->display_price($value['price'], Tax::get_rate($product->get('tax_class_id')))
                   . ') ';
          }
          $option_choices[] = ['id' => $value_id, 'text' => $text];
        }

        $options[] = [
          'id' => $option_id,
          'name' => $attribute['name'],
          'choices' => $option_choices,
          'selection' => is_string($_GET['products_id'])
                       ? $_SESSION['cart']->contents[$_GET['products_id']]['attributes'][$option_id] ?? ''
                       : '',
        ];
      }

      return $options;
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_PI_OA_STATUS' => [
          'title' => 'Enable Options & Attributes',
          'value' => 'True',
          'desc' => 'Should this module be shown on the product info page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_PI_OA_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_PI_OA_HELPER' => [
          'title' => 'Add Helper Text',
          'value' => 'True',
          'desc' => 'Should first option in dropdown be Helper Text?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_PI_OA_ENFORCE' => [
          'title' => 'Enforce Selection',
          'value' => 'True',
          'desc' => 'Should customer be forced to select option(s)?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_PI_OA_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '80',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }

