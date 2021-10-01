<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class bm_search extends abstract_block_module {

    const CONFIG_KEY_BASE = 'MODULE_BOXES_SEARCH_';

    function execute() {
      $form = new Form('quick_find', $GLOBALS['Linker']->build('advanced_search_result.php')->set_include_session(false), 'get');
      $form->hide_session_id()->hide('search_in_description', '0');
      $input = new Input('keywords', ['autocomplete' => 'off', 'placeholder' => TEXT_SEARCH_PLACEHOLDER], 'search');
      $input->require();

      $tpl_data = ['group' => $this->group, 'file' => __FILE__];
      include 'includes/modules/block_template.php';
    }

    protected function get_parameters() {
      return [
        'MODULE_BOXES_SEARCH_STATUS' => [
          'title' => 'Enable Search Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_BOXES_SEARCH_CONTENT_PLACEMENT' => [
          'title' => 'Content Placement',
          'value' => 'Right Column',
          'desc' => 'Should the module be loaded in the left or right column?',
          'set_func' => "Config::select_one(['Left Column', 'Right Column'], ",
        ],
        'MODULE_BOXES_SEARCH_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '5025',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
