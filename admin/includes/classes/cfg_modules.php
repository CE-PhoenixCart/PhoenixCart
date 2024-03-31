<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class cfg_modules {

    private $_modules = [];

    public function __construct() {
      $directory = 'includes/modules/cfg_modules';

      if ($dir = @dir($directory)) {
        while ($file = $dir->read()) {
          if (!is_dir("$directory/$file") && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $class = pathinfo($file, PATHINFO_FILENAME);

            $this->_modules[] = [
              'code' => $class::CODE,
              'directory' => $class::DIRECTORY,
              'language_directory' => $class::LANGUAGE_DIRECTORY,
              'key' => $class::KEY,
              'title' => $class::TITLE,
              'template_integration' => $class::TEMPLATE_INTEGRATION,
              'get_help_link' => $class::GET_HELP_LINK,
              'get_addons_links' => $class::GET_ADDONS_LINKS,
            ];
          }
        }
      }
    }

    public function set(string $code, string $key, $value) {
      foreach ($this->_modules as $m) {
        if ($m['code'] == $code) {
          $m[$key] = $value;
        }
      }
    }

    public function getAll() {
      return $this->_modules;
    }

    public function get(string $code, string $key) {
      foreach ($this->_modules as $m) {
        if ($m['code'] == $code) {
          return $m[$key];
        }
      }
    }

    public function exists(string $code) {
      return $this->get($code, 'code') === $code;
    }

    public function fix_installed_constant($type, $installed_modules) {
      $should_fix = "cfgm_$type::fix_installed_constant";
      if (is_callable($should_fix) && !$should_fix($installed_modules)) {
        return;
      }

      $modules_installed = array_column($installed_modules, 'file');
      $installed = implode(';', $modules_installed);

      if (constant($this->get($type, 'key')) !== $installed) {
        static::update_configuration($installed, $GLOBALS['module_key']);
        $GLOBALS['modules_installed'] = $modules_installed;
      }
    }

    public static function generate_modules() {
      if ($dir = @dir($GLOBALS['module_directory'])) {
        while ($file = $dir->read()) {
          if (!is_dir("{$GLOBALS['module_directory']}$file")) {
            yield $file;
          }
        }

        $dir->close();
      }
    }

    public static function list_modules(string $type) {
      $f = "cfgm_$type::list_modules";
      if (is_callable($f)) {
        return $f();
      }

      $module_files = ['installed' => []];
      $new_modules = [];

      $generator = "cfgm_$type::generate_modules";
      if (!is_callable($generator)) {
        $generator = [static::class, 'generate_modules'];
      }

      foreach ($generator() as $file) {
        $pathinfo = pathinfo($file);
        if (('php' !== $pathinfo['extension']) || (!class_exists($pathinfo['filename']))) {
          continue;
        }

        $module = new $pathinfo['filename']();
        $vars = get_object_vars($module);
        $vars['file'] = $file;
        if ($module->check() > 0) {
          if (($module->sort_order > 0) && !isset($module_files['installed'][$module->sort_order])) {
            $module_files['installed'][$module->sort_order] = $vars;
          } else {
            $module_files['installed'][] = $vars;
          }
        } else {
          $key = $module->title;
          if (isset($module->group)) {
            $key = "{$module->group}-$key";
          }

          $new_modules[$key] = $vars;
        }
      }

      ksort($module_files['installed']);
      ksort($new_modules);

      $module_files['new'] = array_values($new_modules);

      return $module_files;
    }

    public static function update_configuration($value, $key) {
      $GLOBALS['db']->query(sprintf(<<<'EOSQL'
UPDATE configuration
 SET configuration_value = '%s', last_modified = NOW()
 WHERE configuration_key = '%s'
EOSQL
        , $GLOBALS['db']->escape($value), $GLOBALS['db']->escape($key)));
    }

    public static function build_keys($module) {
      if (is_callable([$module, 'build_keys'])) {
        return $module->build_keys();
      }

      $key_values = $GLOBALS['db']->fetch_all(sprintf(<<<'EOSQL'
SELECT
   configuration_key,
   configuration_title AS title,
   configuration_value AS value,
   configuration_description AS description,
   use_function,
   set_function
 FROM configuration
 WHERE configuration_key IN ('%1$s')
 ORDER BY FIELD(configuration_key, '%1$s')
EOSQL
        , implode("', '", array_map([$GLOBALS['db'], 'escape'], $module->keys()))));

      return array_combine(array_column($key_values, 'configuration_key'), $key_values);
    }

    public static function can($module, $action) {
      if (method_exists($module, 'can')) {
        return $module->can($action);
      }

      switch ($action) {
        case 'install':
          $requirements = get_class($module) . '::REQUIRES';
          if (!defined($requirements) || empty($requirements = constant($requirements)) || !is_array($requirements)) {
            return true;
          }

          return $GLOBALS['customer_data']->has($requirements);
        case 'remove':
          $provides = get_class($module) . '::PROVIDES';
          if (!defined($provides)) {
            return true;
          }
          $provides = constant($provides);

          if (empty($provides) || !is_array($provides)) {
            $provides = [];
          }

          // we can remove if nothing requires this module's abilities
          return !$GLOBALS['customer_data']->has_requirements($provides, get_class($module));
        default:
          return true;
      }
    }

    public static function hook_injectBodyStart() {
      $class = "cfgm_{$GLOBALS['set']}";
      if (is_callable([$class, 'hook_injectBodyStart'])) {
        $class::hook_injectBodyStart();
      }
    }

  }
