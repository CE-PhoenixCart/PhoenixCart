<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class bm_languages extends abstract_block_module {

    const CONFIG_KEY_BASE = 'MODULE_BOXES_LANGUAGES_';

    public function execute() {
      if (Text::is_prefixed_by(Request::get_page(), 'checkout')) {
        return;
      }

      if (isset($GLOBALS['lng']) && ($GLOBALS['lng'] instanceof language)) {
        $lng =& $GLOBALS['lng'];
      } else {
        $lng = new language();
      }

      if (count($lng->catalog_languages) > 1) {
        $link = $GLOBALS['Linker']->build()->retain_query_except(['currency']);

        $languages_string = '';
        foreach ($lng->catalog_languages as $key => $value) {
          $image = Text::ltrim_once(
            language::map_to_translation("images/{$value['image']}", $value['directory']),
            DIR_FS_CATALOG);
          $languages_string .= ' <a href="' . $link->set_parameter('language', $key) . '">'
                             . (new Image($image, [], htmlspecialchars($value['name'])))->set_responsive(false)
                             . '</a> ';
        }

        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/block_template.php';
      }
    }

    protected function get_parameters() {
      return [
        'MODULE_BOXES_LANGUAGES_STATUS' => [
          'title' => 'Enable Languages Module',
          'value' => 'True',
          'desc' => 'Do you want to add the module to your shop?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        'MODULE_BOXES_LANGUAGES_CONTENT_PLACEMENT' => [
          'title' => 'Content Placement',
          'value' => 'Right Column',
          'desc' => 'Should the module be loaded in the left or right column?',
          'set_func' => "Config::select_one(['Left Column', 'Right Column'], ",
        ],
        'MODULE_BOXES_LANGUAGES_SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '0',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }
