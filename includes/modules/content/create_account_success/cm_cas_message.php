<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_cas_message extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_CAS_MESSAGE_';

    public function __construct() {
      parent::__construct(__FILE__);
      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
    }

    public function execute() {
      $contact_us_href = $GLOBALS['Linker']->build('contact_us.php');
      $account_href = $GLOBALS['Linker']->build('account.php');

      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_CAS_MESSAGE_STATUS' => [
          'title' => 'Enable Message Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_CAS_MESSAGE_CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => '12',
          'desc' => 'What width container should the content be shown in?',
          'set_func' => "Config::select_one(['12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1'], ",
        ],
        'MODULE_CONTENT_CAS_MESSAGE_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '20',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
