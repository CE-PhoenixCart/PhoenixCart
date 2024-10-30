<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class pi_date_available extends abstract_module {

    const CONFIG_KEY_BASE = 'PI_DATE_AVAILABLE_';

    public $group = 'pi_modules_b';

    public function __construct() {
      parent::__construct();

      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_pi_modular::display_layout() . '</div>';

      if ( $this->enabled ) {
        $this->group = 'pi_modules_' . strtolower(PI_DATE_AVAILABLE_GROUP);
      }
    }

    public function getOutput() {
      $date = $GLOBALS['product']->get('date_available');
      if ($date > date('Y-m-d H:i:s')) {
        $date = (PI_DATE_AVAILABLE_STYLE === 'Long') ? Date::expound($date) : Date::abridge($date);

        $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
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
          'value' => 'C',
          'desc' => 'Where should this module display on the product info page?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12 mb-2',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
		    $this->config_key_base . 'STYLE' => [
          'title' => 'Date Style',
          'value' => 'Long',
          'desc' => 'How should the date look?',
          'set_func' => "Config::select_one(['Long', 'Short'], ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '315',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
