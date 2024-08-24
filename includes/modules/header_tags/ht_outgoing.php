<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_outgoing extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_O_';

    public function __construct() {
      parent::__construct(__FILE__);
      
      $display_pages = Outgoing::show_pages();
      $merge_tags    = Outgoing::merge_tags();

      $display_page_msg = MODULE_HEADER_TAGS_O_PAGES;
      foreach ($display_pages as $d => $p) {
        $display_page_msg .= sprintf(MODULE_HEADER_TAGS_O_PAGES_LIVE, $p);
      }

      $this->description .= '<div class="alert alert-danger">' . $display_page_msg . '</div>';
    }

    public function execute() {
      global $display_pages, $merge_tags;

      $outgoing = [];

// clean queue
      if (in_array(basename(Request::get_page()), $display_pages)) {
        Outgoing::delete();
        Outgoing::parse();
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_O_STATUS' => [
          'title' => 'Enable Queued E-mail Module',
          'value' => 'True',
          'desc' => 'Do you want to enable the this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_O_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
  