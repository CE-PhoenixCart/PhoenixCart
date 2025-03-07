<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cm_navbar extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_CONTENT_NAVBAR_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function execute() {
      if ( defined('MODULE_CONTENT_NAVBAR_INSTALLED') && !Text::is_empty(MODULE_CONTENT_NAVBAR_INSTALLED) ) {
        $navbar_modules = [];

        foreach ( explode(';', MODULE_CONTENT_NAVBAR_INSTALLED) as $nbm ) {
          $class = pathinfo($nbm, PATHINFO_FILENAME);

          $nav = new $class();
          if ( $nav->isEnabled() ) {
            $navbar_modules[] = $nav->getOutput();
          }
        }

        if ( [] !== $navbar_modules ) {
          $styles = [];
          $styles[] = MODULE_CONTENT_NAVBAR_STYLE_BG;
          $styles[] = MODULE_CONTENT_NAVBAR_FIXED;
          $styles[] = MODULE_CONTENT_NAVBAR_COLLAPSE;

          $navbar_style = implode(' ', $styles);

          $tpl_data = [ 'group' => $this->group, 'file' => __FILE__ ];
          include 'includes/modules/content/cm_template.php';
        }
      }

      switch (MODULE_CONTENT_NAVBAR_FIXED) {
        case 'fixed-top':
          $custom_css = '<style>body { padding-top: ' . MODULE_CONTENT_NAVBAR_OFFSET . ' !important; }</style>';
          break;
        case 'fixed-bottom':
          $custom_css = '<style>body { padding-bottom: ' . MODULE_CONTENT_NAVBAR_OFFSET . ' !important; }</style>';
          break;
        default:
          return;
      }

      // workaround; padding needs to be set last
      $GLOBALS['Template']->add_block($custom_css, 'footer_scripts');
    }

    protected function get_parameters() {
      return [
        'MODULE_CONTENT_NAVBAR_STATUS' => [
          'title' => 'Enable Navbar Module',
          'value' => 'True',
          'desc' => 'Should the Navbar be shown? ',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_CONTENT_NAVBAR_STYLE_BG' => [
          'title' => 'Navbar Colour Scheme',
          'value' => 'bg-light navbar-light border-bottom',
          'desc' => 'What background and foreground colour should the Navbar have?  See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/utilities/background/#background-color"><u>background/#background-color</u></a> and <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/components/navbar/#color-schemes"><u>navbar/#color-schemes</u></a>',
        ],
        'MODULE_CONTENT_NAVBAR_FIXED' => [
          'title' => 'Placement',
          'value' => 'default',
          'desc' => 'Should the Navbar be Fixed/Sticky/Default behaviour? See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/components/navbar/#placement"><u>navbar/#placement</u></a>',
          'set_func' => "Config::select_one(['fixed-top', 'fixed-bottom', 'sticky-top', 'default'], ",
        ],
        'MODULE_CONTENT_NAVBAR_OFFSET' => [
          'title' => 'Placement Offset',
          'value' => '4rem',
          'desc' => 'Offset if using fixed-* Placement.',
        ],
        'MODULE_CONTENT_NAVBAR_COLLAPSE' => [
          'title' => 'Collapse',
          'value' => 'navbar-expand-sm',
          'desc' => 'When should the Navbar Show? See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/components/navbar/#how-it-works"><u>navbar/#how-it-works</u></a>',
          'set_func' => "Config::select_one(['navbar-expand', 'navbar-expand-sm', 'navbar-expand-md', 'navbar-expand-lg', 'navbar-expand-xl', 'navbar-expand-xxl'], ",
        ],
        'MODULE_CONTENT_NAVBAR_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '10',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
