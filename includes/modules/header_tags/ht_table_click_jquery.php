<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_table_click_jquery extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_';

    public function __construct() {
      parent::__construct(__FILE__);

      if ($this->base_constant('PLACEMENT') !== 'Header') {
        $this->group = 'footer_scripts';
      }
    }

    public function execute() {
      if (!Text::is_empty($this->base_constant('PAGES'))
        && in_array(basename(Request::get_page()),
             page_selection::_get_pages($this->base_constant('PAGES'))))
      {
        $tr_background = MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_TR_BACKGROUND;
        
        $GLOBALS['Template']->add_block(<<<"EOCSS"
<script>
document.querySelectorAll('tr.table-selection').forEach(function(row) {
  row.addEventListener('click', function() {
    row.closest('table').querySelectorAll('tr.table-selection').forEach(function(r) {
      r.classList.remove('{$tr_background}');
      r.querySelector('input').checked = false;
    });
    row.classList.add('{$tr_background}');
    row.querySelector('input').checked = true;
  });
});
</script>

EOCSS
          , $this->group);
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_STATUS' => [
          'title' => 'Enable Clickable Table Rows Module',
          'value' => 'True',
          'desc' => 'Do you want to enable the Clickable Table Rows module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_PAGES' => [
          'title' => 'Pages',
          'value' => 'checkout_payment.php;checkout_shipping.php',
          'desc' => 'The pages to add the necessary javascript to.',
          'use_func' => 'page_selection::_show_pages',
          'set_func' => 'page_selection::_edit_pages(',
        ],
        'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_TR_BACKGROUND' => [
          'title' => 'Background Colour',
          'value' => 'table-success',
          'desc' => 'The background colour of the clicked Row.  See https://getbootstrap.com/docs/5.3/content/tables/#variants',
        ],
        'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
