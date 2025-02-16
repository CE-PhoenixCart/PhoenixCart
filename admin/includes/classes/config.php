<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Config {

    public static function draw_textarea($text = '') {
      return (new Textarea('configuration_value', ['cols' => '35', 'rows' => 5]))->set_text($text);
    }

    public static function get_zone_name($zone_id, $country_id = null) {
      return is_numeric($zone_id)
           ? Zone::fetch_name($zone_id, $country_id ?? STORE_COUNTRY, CLASS_CONFIG_TEXT_INVALID_ZONE)
           : $zone_id;
    }

    public static function name($key) {
      return $key ? "configuration[$key]" : 'configuration_value';
    }

    public static function select_country($country_id) {
      return (new Select('configuration_value', Country::fetch_options(), ['class' => 'form-select']))->set_selection($country_id);
    }

    public static function select_customer_data_group($id, $key = '') {
      return (new Select(static::name($key), customer_data_group::fetch_options(), ['class' => 'form-select']))->set_selection($id);
    }

    public static function select_geo_zone($zone_class_id, $key) {
      return (new Select(
        static::name($key),
        array_merge(
          [['id' => '0', 'text' => TEXT_NONE]],
          geo_zone::fetch_options()
        ), ['class' => 'form-select']))->set_selection($zone_class_id);
    }

    public static function select_at_least_one($selections, $key_values, $key_name = null) {
      $key_name = static::name($key_name);

      return static::select_none_to_many($selections, $key_values, $key_name);
    }

    public static function select_multiple($selections, $key_values, $key_name = null) {
      $key_name = static::name($key_name);
      return PHP_EOL . new Input($key_name, ['value' => ''], 'hidden')
           . static::select_none_to_many($selections, $key_values, $key_name);
    }

    public static function select_none_to_many($selections, $key_values, $key_name) {
      if (empty($key_values)) {
        $key_values = [];
      } elseif (!is_array($key_values)) {
        if (false === strpos($key_values, ';')) {
          $key_values = [$key_values => true];
        } else {
          $key_values = array_fill_keys(explode(';', $key_values), true);
        }
      }

      $key_name .= '[]';
      $checkbox = new Tickable($key_name, ['class' => 'form-check-input'], 'checkbox');

      $string = ''; $n = 1;
      foreach ($selections as $key => $text) {
        if (is_int($key)) {
          if (is_array($text)) {
            $key = $text['id'];
            $text = $text['text'];
          } else {
            $key = $text;
          }
        }
        
        $checkbox->set('id', 'check_' . $n);
        $checkbox->tick(isset($key_values[$key]) || array_key_exists($key, $key_values));
        $checkbox->set('value', $key);

        $string .= <<<EOT
<div class="form-check">
  $checkbox
  <label class="form-check-label" for="check_{$n}">
    {$text}
  </label>
</div>
EOT;

        $n++;
      }

      return $string;
    }

    public static function select_one($select_options, $key_value, $key = '') {
      $string = ''; $n = 1;
      foreach ($select_options as $select_option) {
        $radio = new Tickable(static::name($key), ['id' => 'select_one_' . $n, 'value' => $select_option, 'class' => 'form-check-input'], 'radio');

        if ($key_value == $select_option) {
          $radio->tick();
        }

        $string .= "<div class=\"form-check\">$radio<label class=\"form-check-label\" for=\"select_one_$n\">$select_option</label></div>";
        
        $n++;
      }

      return $string;
    }

    public static function select_order_status($order_status_id, $key = '') {
      $statuses = array_merge(
        [['id' => '0', 'text' => TEXT_DEFAULT]],
        $GLOBALS['db']->fetch_all(sprintf(<<<'EOSQL'
SELECT orders_status_id AS id, orders_status_name AS text
 FROM orders_status
 WHERE language_id = %d
 ORDER BY orders_status_name
EOSQL
        , (int)$_SESSION['languages_id'])));

      return (new Select(static::name($key), $statuses, ['class' => 'form-select']))->set_selection($order_status_id);
    }

    public static function select_tax_class($tax_class_id, $key = '') {
      $name = static::name($key);

      return (new Select($name, array_merge(
        [['id' => '0', 'text' => TEXT_NONE]],
        Tax::fetch_classes()), ['class' => 'form-select']))->set_selection($tax_class_id);
    }

    public static function select_template($key_value, $key = null) {
      $templates = [];
      foreach (scandir(DIR_FS_CATALOG . 'templates', SCANDIR_SORT_ASCENDING) as $template) {
        if ('.' !== $template[0]) {
          $templates[] = $template;
        }
      }

      return static::select_one($templates, $key_value, $key);
    }

    public static function select_zone_by($country_id = STORE_COUNTRY, $zone_id = '') {
      $zones = Zone::fetch_by_country($country_id);
      return (is_array($zones) && count($zones))
           ? (new Select('configuration_value', $zones, ['class' => 'form-select']))->set_selection($zone_id)
           : new Input('configuration_value', ['value' => $zone_id]);
    }

  }
