<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_info_modular extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_INFO_MODULAR_';

    public function __construct() {
      parent::__construct(__FILE__);
      
      $this->description .= cm_info_modular::display_layout();
    }

    public function execute() {
      $slots = [
        'a' => $this->base_constant('A_WIDTH'),
        'b' => $this->base_constant('B_WIDTH'),
        'c' => $this->base_constant('C_WIDTH'),
        'd' => $this->base_constant('D_WIDTH'),
        'e' => $this->base_constant('E_WIDTH'),
        'f' => $this->base_constant('F_WIDTH'),
        'g' => $this->base_constant('G_WIDTH'),
        'h' => $this->base_constant('H_WIDTH'),
        'i' => $this->base_constant('I_WIDTH'),
      ];

      if ( defined('MODULE_CONTENT_INFO_INSTALLED') && !Text::is_empty(MODULE_CONTENT_INFO_INSTALLED) ) {
        $ip_modules = array_filter(array_map(function ($ipm) {
          $class = pathinfo($ipm, PATHINFO_FILENAME);

          return new $class();
        }, explode(';', MODULE_CONTENT_INFO_INSTALLED)), function ($ip) {
          return $ip->isEnabled();
        });

        if ( count($ip_modules) > 0 ) {
          array_walk($ip_modules, function ($v, $unused) { $v->getOutput(); });

          $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
          include 'includes/modules/content/cm_template.php';
        }
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
        $this->config_key_base . 'A_WIDTH' => [
          'title' => 'Slot Width: A',
          'value' => 'col-sm-12',
          'desc' => 'What width should Slot A be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'B_WIDTH' => [
          'title' => 'Slot Width: B',
          'value' => 'col-sm-6',
          'desc' => 'What width should Slot B be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'C_WIDTH' => [
          'title' => 'Slot Width: C',
          'value' => 'col-sm-6',
          'desc' => 'What width should Slot C be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'D_WIDTH' => [
          'title' => 'Slot Width: D',
          'value' => 'col-sm-4',
          'desc' => 'What width should Slot D be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'E_WIDTH' => [
          'title' => 'Slot Width: E',
          'value' => 'col-sm-4',
          'desc' => 'What width should Slot E be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'F_WIDTH' => [
          'title' => 'Slot Width: F',
          'value' => 'col-sm-4',
          'desc' => 'What width should Slot F be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'G_WIDTH' => [
          'title' => 'Slot Width: G',
          'value' => 'col-sm-6',
          'desc' => 'What width should Slot G be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'H_WIDTH' => [
          'title' => 'Slot Width: H',
          'value' => 'col-sm-6',
          'desc' => 'What width should Slot H be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'I_WIDTH' => [
          'title' => 'Slot Width: I',
          'value' => 'col-sm-12',
          'desc' => 'What width should Slot I be?  Note that Slots in a Row should totalise 12.',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '59',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

    public static function display_layout() {
      if ( !defined('MODULE_CONTENT_INFO_MODULAR_STATUS') ) {
        return null;
      }

      $slots = [
        'A' => ['width' => MODULE_CONTENT_INFO_MODULAR_A_WIDTH],
        'B' => ['width' => MODULE_CONTENT_INFO_MODULAR_B_WIDTH],
        'C' => ['width' => MODULE_CONTENT_INFO_MODULAR_C_WIDTH],
        'D' => ['width' => MODULE_CONTENT_INFO_MODULAR_D_WIDTH],
        'E' => ['width' => MODULE_CONTENT_INFO_MODULAR_E_WIDTH],
        'F' => ['width' => MODULE_CONTENT_INFO_MODULAR_F_WIDTH],
        'G' => ['width' => MODULE_CONTENT_INFO_MODULAR_G_WIDTH],
        'H' => ['width' => MODULE_CONTENT_INFO_MODULAR_H_WIDTH],
        'I' => ['width' => MODULE_CONTENT_INFO_MODULAR_I_WIDTH],
      ];

      return modular::display_layout($slots);
    }

    public function install($parameter_key = null) {
      if (!defined('MODULE_CONTENT_INFO_INSTALLED')) {
        $GLOBALS['db']->query(<<<'EOSQL'
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added)
 VALUES ('Installed Modules', 'MODULE_CONTENT_INFO_INSTALLED', '', 'This is automatically updated. No need to edit.', 6, 0, NOW())
EOSQL
          );
      }

      parent::install($parameter_key);
    }

  }
