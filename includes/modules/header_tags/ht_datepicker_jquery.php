<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_datepicker_jquery extends abstract_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_DATEPICKER_JQUERY_';

    protected $group = 'footer_scripts';

    public function execute() {
      global $Template;

      if (!Text::is_empty($this->base_constant('PAGES'))) {
        if (in_array(basename(Request::get_page()), page_selection::_get_pages($this->base_constant('PAGES')))) {
          $Template->add_block('<script src="ext/datepicker/js/bootstrap-datepicker.min.js"></script>', $this->group);
          $Template->add_block('<link rel="stylesheet" href="ext/datepicker/css/bootstrap-datepicker.min.css" />', 'header_tags');
          // create_account
          // account edit
          $Template->add_block('<script>$(\'#dob\').datepicker({endDate: "+0d", startView: 2});</script>', $this->group);
        }
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_DATEPICKER_JQUERY_STATUS' => [
          'title' => 'Enable Datepicker jQuery Module',
          'value' => 'True',
          'desc' => 'Do you want to enable the Datepicker module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_DATEPICKER_JQUERY_PAGES' => [
          'title' => 'Pages',
          'value' => 'advanced_search.php;account_edit.php;create_account.php',
          'desc' => 'The pages to add the Datepicker jQuery Scripts to.',
          'use_func' => 'page_selection::_show_pages',
          'set_func' => 'page_selection::_edit_pages(',
        ],
        'MODULE_HEADER_TAGS_DATEPICKER_JQUERY_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
