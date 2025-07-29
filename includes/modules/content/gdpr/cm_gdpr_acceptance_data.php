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
      
      $customer_id = (int)$_SESSION['customer_id'];
      $agreed_pages = ['privacy', 'conditions'];

      $escaped_slugs = array_map([$GLOBALS['db'], 'escape'], $agreed_pages);
      $slug_list = "'" . implode("','", $escaped_slugs) . "'";

      $acceptance_query = $GLOBALS['db']->fetch_all("
        SELECT *
        FROM customers_gdpr
        WHERE customers_id = {$customer_id}
          AND slug IN ({$slug_list})
        ORDER BY slug, gdpr_id DESC
      ");

      $agreed = [];
      foreach ($acceptance_query as $entry) {
        $slug = $entry['slug'];
        if (!isset($agreed[$slug])) {
          $agreed[$slug] = $entry;
        }
      }

      foreach ($agreed_pages as $slug) {
        if (!isset($agreed[$slug])) {
          continue;
        }

        $entry = $agreed[$slug];
        $key = strtoupper($slug);

        $GLOBALS['port_my_data']['YOU']['ACCEPTED']['DOCUMENT'][$key] = [
          'TEXT'     => $entry['pages_text'],
          'TITLE'    => $entry['pages_title'],
          'WRITTEN'  => $entry['timestamp'],
          'LANGUAGE' => $entry['language'],
          'ACCEPTED' => $entry['date_added'],
        ];
      }
      
      if (!empty($agreed)) {
        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/content/cm_template.php';
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
