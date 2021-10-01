<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class bm_currencies extends abstract_block_module {

    const CONFIG_KEY_BASE = 'MODULE_BOXES_CURRENCIES_';

    public function execute() {
      global $currencies;

      if (isset($currencies) && is_object($currencies) && (count($currencies->currencies) > 1)
        && !Text::is_prefixed_by(basename(Request::get_page()), 'checkout'))
      {
        $currency_options = [];
        foreach ($currencies->currencies as $key => $value) {
          $currency_options[] = ['id' => $key, 'text' => $value['title']];
        }

        $form = new Form('currencies', $GLOBALS['Linker']->build()->set_include_session(false), 'get');
        $form->hide_session_id();
        $excludes = array_flip(['currency', 'x', 'y', session_name()]);
        foreach (array_filter(array_diff_key($_GET, $excludes), 'is_string') as $key => $value) {
          $form->hide($key, $value);
        }

        $menu = (new Select('currency', $currency_options, ['onchange' => 'this.form.submit();']))->set_selection($_SESSION['currency']);

        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/block_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_BOXES_CURRENCIES_STATUS' => [
          'title' => 'Enable Currencies Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT' => [
          'title' => 'Content Placement',
          'value' => 'Right Column',
          'desc' => 'Should the module be loaded in the left or right column?',
          'set_func' => "Config::select_one(['Left Column', 'Right Column'], ",
        ],
        'MODULE_BOXES_CURRENCIES_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
