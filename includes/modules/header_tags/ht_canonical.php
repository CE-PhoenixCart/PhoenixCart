<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class ht_canonical extends abstract_executable_module {

    const CONFIG_KEY_BASE = 'MODULE_HEADER_TAGS_CANONICAL_';

    public function __construct() {
      parent::__construct(__FILE__);
    }

    public function build_link() {
      switch (basename(Request::get_page())) {
        case 'index.php':
          if (isset($GLOBALS['cPath']) && !Text::is_empty($GLOBALS['cPath'])
            && ($GLOBALS['current_category_id'] > 0)
            && ($GLOBALS['category_depth'] != 'top'))
          {
            $canonical = Guarantor::ensure_global('category_tree')->find_path($GLOBALS['current_category_id']);

            return $GLOBALS['Linker']->build('index.php', ['view' => 'all', 'cPath' => $canonical], false);
          } elseif (isset($_GET['manufacturers_id']) && !Text::is_empty($_GET['manufacturers_id'])) {
            return $GLOBALS['Linker']->build('index.php', ['view' => 'all', 'manufacturers_id' => (int)$_GET['manufacturers_id']], false);
          }

          return $GLOBALS['Linker']->build('index.php', [], false);

        case 'product_info.php':
          return $GLOBALS['Linker']->build('product_info.php', ['products_id' => (int)$_GET['products_id']], false);

        case 'products_new.php':
        case 'specials.php':
          return $GLOBALS['Linker']->build(null, ['view' => 'all'], false);

        case 'info.php':
          if (isset($_GET['pages_id'])) {
            return $GLOBALS['Linker']->build(null, [
              'pages_id' => (int) $_GET['pages_id']
            ], false);
          }
          // otherwise fall through to
        default:
          return $GLOBALS['Linker']->build(null, [], false);
      }
    }

    public function execute() {
      $GLOBALS['Template']->add_block('<link rel="canonical" href="' . $this->build_link() . '" />' . PHP_EOL, $this->group);
    }

    protected function get_parameters() {
      return [
        'MODULE_HEADER_TAGS_CANONICAL_STATUS' => [
          'title' => 'Enable Canonical Module',
          'value' => 'True',
          'desc' => 'Do you want to enable the Canonical module?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_HEADER_TAGS_CANONICAL_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
