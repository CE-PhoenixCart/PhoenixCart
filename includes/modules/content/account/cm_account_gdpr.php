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

    protected $public_title = MODULE_CONTENT_ACCOUNT_GDPR_LINK_TITLE;

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      if (isset($_SESSION['customer_id'])) {
        $geo_location = $GLOBALS['customer']->get('country_id');

        $GLOBALS['Template']->_data['account']['gdpr'] = [
          'title' => $this->public_title,
          'sort_order' => 100,
          'links' => [],
        ];

        if (Text::is_empty(MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES)
          || in_array($geo_location, explode(';', MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES)))
        {
          $GLOBALS['Template']->_data['account']['gdpr']['links'][$this->group] = [
            'title' => MODULE_CONTENT_ACCOUNT_GDPR_SUB_TITLE,
            'link' => $GLOBALS['Linker']->build('gdpr.php'),
            'icon' => 'fa fa-user-secret fa-5x',
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
    return implode("<br />\n", array_map('Country::fetch_name', explode(';', $text)));
  }

  function gdpr_select_countries($values, $key) {
    $values_array = explode(';', $values);

    $output = '';
    foreach (Country::fetch_options() as $country) {
      $tickable = new Tickable('gdpr_selected_countries[]', ['value' => $country['id']], 'checkbox');
      if (in_array($country['id'], $values_array)) {
        $tickable->tick();
      }
      $output .= '<br />' . $tickable . '&nbsp;' . Text::output($country['text']) . PHP_EOL;
    }

    $output .= new Input('configuration[' . $key . ']', ['id' => 'gdpr_countrys'], 'hidden') . PHP_EOL;

    $output .= <<<'EOSQL'
<script>
  function gdpr_update_cfg_value() {
    var gdpr_selected_countries = '';

    if ($('input[name="gdpr_selected_countries[]"]').length > 0) {
      $('input[name="gdpr_selected_countries[]"]:checked').each(function() {
        gdpr_selected_countries += $(this).attr('value') + ';';
      });

      if (gdpr_selected_countries.length > 0) {
        gdpr_selected_countries = gdpr_selected_countries.substring(0, gdpr_selected_countries.length - 1);
      }
    }

    $('#gdpr_countrys').val(gdpr_selected_countries);
  }

  $(function() {
    gdpr_update_cfg_value();

    if ($('input[name="gdpr_selected_countries[]"]').length > 0) {
      $('input[name="gdpr_selected_countries[]"]').change(function() {
        gdpr_update_cfg_value();
      });
    }
  });
</script>

EOSQL;

    return $output;
  }
