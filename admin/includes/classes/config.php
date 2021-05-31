<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Config {

    public static function name($key) {
      return $key ? "configuration[$key]" : 'configuration_value';
    }

    public static function draw_textarea($text = '') {
      return (new Textarea('configuration_value', ['cols' => '35', 'rows' => 5]))->set_text($text);
    }

    public static function select_one($select_options, $key_value, $key = '') {
      $string = '';
      foreach ($select_options as $select_option) {
        $radio = new Tickable(static::name($key), ['value' => $select_option, 'class' => ''], 'radio');

        if ($key_value == $select_option) {
          $radio->tick();
        }

        $string .= "<br><label>$radio $select_option</label>";
      }

      return $string;
    }

    public static function select_multiple($selections, $key_values, $key_name = null) {
      if (empty($key_values)) {
        $key_values = [];
      } elseif (!is_array($key_values)) {
        if (false === strpos($key_values, ';')) {
          $key_values = [$key_values => true];
        } else {
          $key_values = array_fill_keys(explode(';', $key_values), true);
        }
      }

      $key_name = static::name($key_name) . '[]';
      $checkbox = new Tickable($key_name, ['class' => ' '], 'checkbox');

      $string = '';
      foreach ($selections as $key => $text) {
        if (is_int($key)) {
          $key = $text;
        }

        if (isset($key_values[$key]) || array_key_exists($key, $key_values)) {
          $checkbox->tick();
        } else {
          $checkbox->delete('checked');
        }

        $string .= PHP_EOL . '<br><label>' . $checkbox->set('value', $key) . ' ' . $text . '</label>';
      }

      return $string;
    }

    public static function select_country($country_id) {
      return (new Select('configuration_value', Country::fetch_options()))->set_selection($country_id);
    }

    public static function select_customer_data_group($id, $key = '') {
      return (new Select(static::name($key), customer_data_group::fetch_options()))->set_selection($id);
    }

    public static function select_geo_zone($zone_class_id, $key) {
      return (new Select(
        static::name($key),
        array_merge(
          [['id' => '0', 'text' => TEXT_NONE]],
          geo_zone::fetch_options()
        )))->set_selection($zone_class_id);
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

      return (new Select(static::name($key), $statuses))->set_selection($order_status_id);
    }

    public static function select_tax_class($tax_class_id, $key = '') {
      $name = static::name($key);

      return (new Select($name, array_merge(
        [['id' => '0', 'text' => TEXT_NONE]],
        Tax::fetch_classes())))->set_selection($tax_class_id);
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
      return ($zones = Zone::fetch_by_country($country_id))
           ? (new Select('configuration_value', $zones))->set_selection($zone_id)
           : new Input('configuration_value', $zone_id);
    }

  }
