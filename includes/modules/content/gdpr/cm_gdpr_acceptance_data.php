<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_gdpr_acceptance_data extends abstract_executable_module { 

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_GDPR_ACCEPTANCE_DATA_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $port_my_data;
      
      $agreed_pages = ['privacy', 'conditions'];
      
      $acceptance_query = $GLOBALS['db']->fetch_all(sprintf(<<<'EOSQL'
SELECT * FROM customers_gdpr WHERE customers_id = %s ORDER BY gdpr_id DESC
EOSQL
          , (int)$_SESSION['customer_id']));

      $agreed = []; 
      foreach ($acceptance_query as $k => $v) {
        $agreed[$v['slug']][] = $v;
      }
      
      foreach ($agreed_pages as $k => $v) {
        $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['TEXT'] = $agreed[$v][0]['pages_text'];
        $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['TITLE'] = $agreed[$v][0]['pages_title'];
        $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['WRITTEN'] = $agreed[$v][0]['timestamp'];
        $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['LANGUAGE'] = $agreed[$v][0]['language'];
        $port_my_data['YOU']['ACCEPTED']['DOCUMENT'][strtoupper($v)]['ACCEPTED'] = $agreed[$v][0]['date_added'];
      }
      
      $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
      include 'includes/modules/content/cm_template.php';
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Container',
          'value' => 'col-sm-12',
          'desc' => 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '205',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
