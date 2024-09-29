<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_account_gdpr_nuke extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_ACCOUNT_GDPR_NUKE_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      global $customer;

      if (isset($_SESSION['customer_id'])) {
        $nuke =& $GLOBALS['Template']->_data['account']['gdpr']['links'];

        if (strlen(MODULE_CONTENT_ACCOUNT_GDPR_NUKE_COUNTRIES) > 0) {
          $eu_countries = explode(';', MODULE_CONTENT_ACCOUNT_GDPR_NUKE_COUNTRIES);
          $geo_location = $customer->get('country_id');

          if (in_array($geo_location, $eu_countries)) {
            $GLOBALS['Template']->_data['account']['gdpr']['links']['nuke_account'] = [
              'title' => MODULE_CONTENT_ACCOUNT_GDPR_NUKE_LINK_TITLE,
              'link' => $GLOBALS['Linker']->build('ext/modules/content/account/nuke_account.php'),
              'icon' => 'fa fa-trash fa-5x text-danger',
            ];
          }
        }
        else {
          $GLOBALS['Template']->_data['account']['gdpr']['links']['nuke_account'] = [
            'title' => MODULE_CONTENT_ACCOUNT_GDPR_NUKE_LINK_TITLE,
            'link' => $GLOBALS['Linker']->build('ext/modules/content/account/nuke_account.php'),
            'icon' => 'fa fa-trash fa-5x text-danger',
          ];
        }
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Nuke Account Module',
          'value' => 'True',
          'desc' => 'Do you want to enable this module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'COUNTRIES' => [
          'title' => 'Countries',
          'value' => '',
          'desc' => 'Restrict the Link to Account Holders in these Countries.  Leave Blank to show link to all Countries!',
          'use_func' => 'cm_account_gdpr_nuke::show_countries',
          'set_func' => "Config::select_multiple(Country::fetch_options(), ",
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '50',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

    public static function show_countries($text) {
      return Text::is_empty($text)
           ? ''
           : nl2br(implode("\n", array_map('Country::fetch_name', explode(';', $text))));
    }

  }
