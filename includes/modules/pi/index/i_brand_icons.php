<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class i_brand_icons extends abstract_module {

    const CONFIG_KEY_BASE = 'I_BRAND_ICONS_';

    public $group = 'i_modules_b';

    public function __construct() {
      parent::__construct();

      $this->group = basename(dirname(__FILE__));

      $this->description .= '<div class="alert alert-warning">' . MODULE_CONTENT_BOOTSTRAP_ROW_DESCRIPTION . '</div>';
      $this->description .= '<div class="alert alert-info">' . cm_i_modular::display_layout() . '</div>';

      if ( $this->enabled ) {
        $this->group = 'i_modules_' . strtolower(I_BRAND_ICONS_GROUP);
      }
    }

    public function getOutput() {
      if (!Text::is_empty(I_BRAND_ICONS_CSV)) {
        $brands = implode(',', explode(';', I_BRAND_ICONS_CSV));

        $brand_query = $GLOBALS['db']->query("select * from manufacturers where manufacturers_id IN (" . $brands . ") order by manufacturers_id");

        $i_brand_output = NULL;
        $i_brand_xs_arr = [];
        while ($brand = $brand_query->fetch_assoc()) {
          $i_brand_output .= '<a class="list-group-item border-0 p-2" href="' . $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => (int)$brand['manufacturers_id']]) . '">'
                           . new Image('images/' . $brand['manufacturers_image'], [], htmlspecialchars($brand['manufacturers_name'])) . '</a>';

          $i_brand_xs_arr[] = $brand;
        }

        $n = 1;
        $i_brand_xs_output = null;
        $i_brand_xs_array = array_chunk($i_brand_xs_arr, (int)I_BRAND_ICONS_XS_CHUNK);

        foreach ($i_brand_xs_array as $i => $o) {
          $active = ($n == 1) ? ' active' : null;
          $i_brand_xs_output .= '<div class="carousel-item' . $active . '">';
            $i_brand_xs_output .= '<div class="list-group list-group-horizontal justify-content-center">';
              foreach ($o as $item) {
                $i_brand_xs_output .= '<a class="list-group-item border-0" href="' . $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => (int)$item['manufacturers_id']]) . '">'
                                    . new Image('images/' . $item['manufacturers_image'], [], htmlspecialchars($item['manufacturers_name'])) . '</a>';
              }
            $i_brand_xs_output .= '</div>';
          $i_brand_xs_output .= '</div>';

          $n++;
        }

        $tpl_data = ['group' => $this->group, 'file' => __FILE__];
        include 'includes/modules/block_template.php';
      }
    }

    protected function get_parameters() {
      return [
        $this->config_key_base . 'STATUS' => [
          'title' => 'Enable Brand Icons Module',
          'value' => 'True',
          'desc' => 'Should this module be shown on the index page?',
          'set_func' => "Config::select_one(['True', 'False'], ",
        ],
        $this->config_key_base . 'GROUP' => [
          'title' => 'Module Display',
          'value' => 'A',
          'desc' => 'Where should this module display on the index page?',
          'set_func' => "Config::select_one(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'], ",
        ],
        $this->config_key_base . 'CONTENT_WIDTH' => [
          'title' => 'Content Width',
          'value' => 'col-sm-4 mb-4',
          'desc' => 'What width container should the content be shown in?',
        ],
        $this->config_key_base . 'CSV' => [
          'title' => 'Brands',
          'value' => '',
          'desc' => 'Display these Brands.',
          'set_func' => "i_select_brands(",
          'use_func' => "i_show_brands",
        ],
        $this->config_key_base . 'XS_CHUNK' => [
          'title' => 'Chunk',
          'value' => '2',
          'desc' => 'At SM and below, the display will change to a Carousel.  This number determines how many icons are in each slide.',
        ],
        $this->config_key_base . 'SORT_ORDER' => [
          'title' => 'Sort Order',
          'value' => '87',
          'desc' => 'Sort order of display. Lowest is displayed first.',
        ],
      ];
    }

  }

  function i_get_brand_name($key) {
    $brand = $GLOBALS['db']->query("select manufacturers_name from manufacturers where manufacturers_id = " . (int)$key . " limit 1")->fetch_assoc();

    return $brand['manufacturers_name'];
  }

  function i_get_manufacturers($manufacturers = []) {
    return array_merge(is_array($manufacturers) ? $manufacturers : [],
      $GLOBALS['db']->fetch_all("SELECT manufacturers_id AS id, manufacturers_name AS text FROM manufacturers ORDER BY manufacturers_name"));
  }

  function i_show_brands($text) {
    return nl2br(implode("\n", array_map('i_get_brand_name', explode(';', $text))));
  }

  function i_select_brands($values, $key) {
    $values_array = explode(';', $values);

    $output = '';
    foreach (i_get_manufacturers() as $brand) {
      $output .= '<br />'
               . (new Tickable('i_selected_brands[]', ['value' => $brand['id']], 'checkbox'))->tick(in_array($brand['id'], $values_array))
               . '&nbsp;' . Text::output($brand['text']);
    }

    $output .= new Input('configuration[' . $key . ']', ['id' => 'i_brands'], 'hidden');

    $output .= '<script>
                function i_update_cfg_value() {
                  var i_selected_brands = \'\';

                  if ($(\'input[name="i_selected_brands[]"]\').length > 0) {
                    $(\'input[name="i_selected_brands[]"]:checked\').each(function() {
                      i_selected_brands += $(this).attr(\'value\') + \';\';
                    });

                    if (i_selected_brands.length > 0) {
                      i_selected_brands = i_selected_brands.substring(0, i_selected_brands.length - 1);
                    }
                  }

                  $(\'#i_brands\').val(i_selected_brands);
                }

                $(function() {
                  i_update_cfg_value();

                  if ($(\'input[name="i_selected_brands[]"]\').length > 0) {
                    $(\'input[name="i_selected_brands[]"]\').change(function() {
                      i_update_cfg_value();
                    });
                  }
                });
                </script>';

    return $output;
  }


