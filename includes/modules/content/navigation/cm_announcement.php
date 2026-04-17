<?php
/*
  $Id$

  Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_announcement extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_ANNOUNCEMENT_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Announcement Module',
          'value' => 'True',
          'desc' => 'Should this Module be shown? ',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'STYLE_BG' => [
          'title' => 'Colour Scheme',
          'value' => 'text-bg-dark',
          'desc' => 'What colour scheme should this Module have?  See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/utilities/background/#background-color"><u>background/#background-color</u></a> and <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/components/navbar/#color-schemes"><u>navbar/#color-schemes</u></a>',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '5',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
