<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class pi_options_attributes extends abstract_module {

    const CONFIG_KEY_BASE = 'PI_OA_';

    public $group = 'pi_modules_c';

    function __construct() {
      parent::__construct();

      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_pi_modular::display_layout() . '</div>';

      if ( $this->enabled ) {
        $this->group = 'pi_modules_' . strtolower(PI_OA_GROUP);
      }
    }

    function getOutput() {
      $options = $this->build_options();
      if (count($options)) {
        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/block_template.php';
      }
    }

    public function build_options() {
      global $currencies, $product;

      $attributes = $product->get('attributes');
      $options = [];
      foreach ($attributes as $option_id => $attribute) {
        $option_choices = [];

        if ((PI_OA_ENFORCE === 'True') && (PI_OA_HELPER === 'True')) {
          $option_choices[] = ['id' => '', 'text' => PI_OA_ENFORCE_SELECTION];
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
        $this->config_key_base . 'HELPER' => [
          'title' => 'Add Helper Text',
          'value' => 'True',
          'desc' => 'Should first option in dropdown be Helper Text?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'ENFORCE' => [
          'title' => 'Enforce Selection',
          'value' => 'True',
          'desc' => 'Should customer be forced to select option(s)?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '310',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }

