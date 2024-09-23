<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_account_gdpr extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_ACCOUNT_GDPR_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      if (isset($_SESSION['customer_id'])) {
        $geo_location = $GLOBALS['customer']->get('country_id');

        $GLOBALS['Template']->_data['account']['gdpr'] = [
          'title' => MODULE_CONTENT_ACCOUNT_GDPR_LINK_TITLE,
          'sort_order' => 100,
          'links' => [],
        ];

        if (Text::is_empty(MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES)
          || in_array($geo_location, explode(';', MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES)))
        {
          $GLOBALS['Template']->_data['account']['gdpr']['links'][$this->group] = [
            'title' => MODULE_CONTENT_ACCOUNT_GDPR_SUB_TITLE,
            'link' => $GLOBALS['Linker']->build('gdpr.php'),
            'icon' => 'fas fa-address-card fa-5x',
          ];
        }
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_ACCOUNT_GDPR_STATUS' => [
          'title' => 'Enable GDPR Link',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES' => [
          'title' => 'Countries',
          'value' => '',
          'desc' => 'Restrict the Link to Account Holders in these Countries.  Leave Blank to show link to all Countries!',
          'use_func' => 'gdpr_show_countries',
          'set_func' => 'Config::select_multiple(Country::fetch_options(), ',
        ],
        'MODULE_CONTENT_ACCOUNT_GDPR_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '10',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }

  function gdpr_show_countries($text) {
    return Text::is_empty($text)
         ? TEXT_ALL
         : implode("<br />\n", array_map('Country::fetch_name', explode(';', $text)));
  }
