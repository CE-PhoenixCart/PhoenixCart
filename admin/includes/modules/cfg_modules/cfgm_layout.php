<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class cfgm_layout extends abstract_page_cfgm {

    const CODE = 'layout';
    const DIRECTORY = DIR_FS_CATALOG . 'includes/modules/pi/';
    const KEY = 'MODULE_LAYOUT_INSTALLED';
    const TITLE = MODULE_CFG_MODULE_LAYOUT_TITLE;
    const GROUP_KEYS = [
      'contact_us' => 'MODULE_CONTENT_CU_INSTALLED',
      'index' => 'MODULE_CONTENT_I_INSTALLED',
      'product_info' => 'MODULE_CONTENT_PI_INSTALLED',
      'info' => 'MODULE_CONTENT_INFO_INSTALLED',
    ];
    
    const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Layout';
    const GET_ADDONS_LINKS = [ADDONS_FREE => 'https://phoenixcart.org/forum/app.php/addons/free/layout-26',
                              ADDONS_COMMERCIAL => 'https://phoenixcart.org/forum/app.php/addons/commercial/layout-32',
                              ADDONS_PRO => 'https://phoenixcart.org/forum/app.php/addons/supporters/layout-44',];

    protected static function is_module_installed($module) {
      if (!isset($GLOBALS[$module]) || !($GLOBALS[$module] instanceof $module)) {
        $GLOBALS[$module] = new $module();
      }

      return $GLOBALS[$module]->isEnabled();
    }

    public static function fix_installed_constant(&$installed_modules) {
      if (empty($_GET['page'])) {
        if (empty($_GET['module'])) {
          foreach (static::GROUP_KEYS as $key) {
            if (defined($key)) {
              $GLOBALS['modules_installed'] = array_merge(
                $GLOBALS['modules_installed'],
                explode(';', constant($key)));
            }
          }

          return false;
        }

        if (!class_exists($_GET['module'])) {
          return false;
        }

        $page = basename(dirname($GLOBALS['class_index']->get($_GET['module'])));
      } else {
        $page = $_GET['page'];
      }

      $key = static::GROUP_KEYS[pathinfo($page, PATHINFO_FILENAME)] ?? null;

      if (is_null($key)) {
        error_log("No key found for '$page'");
        return false;
      }

      $GLOBALS['cfg_modules']->set(static::CODE, 'key', $key);
      $GLOBALS['module_key'] = $key;
      $installed_modules = array_filter($installed_modules, function ($v) use ($page) {
        if (isset($v['file']) && !isset($v['group'], $v['code'])) {
          $v['code'] = pathinfo($v['file'], PATHINFO_FILENAME);
          $v['group'] = basename(dirname($GLOBALS['class_index']->get($v['code'])));
        }

        return ($v['group'] == $page) && static::is_module_installed($v['code']);
      });

      $installed = implode(';', array_column($installed_modules, 'file'));
      if (constant($key) !== $installed) {
        cfg_modules::update_configuration($installed, $key);
      }

      return false;
    }

    public static function generate_modules() {
      if (empty($_GET['page'])) {
        foreach (static::generate_pages() as $page) {
          yield from static::generate_modules_for($page);
        }
      } else {
        $key = static::GROUP_KEYS[pathinfo($_GET['page'], PATHINFO_FILENAME)]
            ?? null;
        $GLOBALS['cfg_modules']->set(static::CODE, 'key', $key);
        $GLOBALS['module_key'] = $key;

        yield from static::generate_modules_for(Text::input($_GET['page']));
      }
    }

    public static function list_modules() {
      if (!empty($_GET['page'])) {
        $key = static::GROUP_KEYS[pathinfo($_GET['page'], PATHINFO_FILENAME)]
            ?? null;
        $GLOBALS['cfg_modules']->set(static::CODE, 'key', $key);
      }

      $installed_modules = [];
      $new_modules = [];

      foreach (static::generate_modules() as $page => $file) {
        $pathinfo = pathinfo($file);
        if (('php' !== $pathinfo['extension']) || (!class_exists($pathinfo['filename']))) {
          continue;
        }

        $module = new $pathinfo['filename']();
        if ($module->check() > 0) {
          $key = "{$page}-{$module->sort_order}-{$module->title}-" . count($installed_modules);

          $installed_modules[$key] = get_object_vars($module);
          $installed_modules[$key]['status'] = $module->check();
          $installed_modules[$key]['group'] = $page;

          if (is_callable([$module, 'get_group']) && !isset($installed_modules[$key]['group'])) {
            $installed_modules[$key]['group'] = $module->get_group();
          }
          $installed_modules[$key]['file'] = "{$installed_modules[$key]['code']}.php";

          if ($module->base_constant('CONTENT_WIDTH') && !isset($installed_modules[$key]['content_width'])) {
            $installed_modules[$key]['content_width'] = $module->base_constant('CONTENT_WIDTH');
          }
          
          $installed_modules[$key]['display_group'] = $module->base_constant('GROUP');
        } else {
          $key = "{$page}-{$module->title}-" . count($new_modules);

          $new_modules[$key] = get_object_vars($module);
          $new_modules[$key]['status'] = $module->check();
          $new_modules[$key]['group'] = $page;
          $new_modules[$key]['file'] = "{$new_modules[$key]['code']}.php";
        }
      }

      ksort($installed_modules, SORT_NATURAL);
      ksort($new_modules, SORT_NATURAL);

      return ['installed' => array_values($installed_modules), 'new' => array_values($new_modules)];
    }

    public static function hook_injectBodyStart() {
      array_splice($GLOBALS['table_definition']['columns'], 1, 0, [
        [
          'name' => TABLE_HEADING_GROUP,
          'function' => function ($row) {
            return $row['group'];
          },
        ],
      ]);

      if (!isset($_GET['list']) || ('new' !== $_GET['list'])) {
        array_splice($GLOBALS['table_definition']['columns'], 3, 0, [
          [
            'name' => TABLE_HEADING_WIDTH,
            'class' => 'text-end',
            'function' => function ($row) {
              return $row['content_width'];
            },
          ],
        ]);
        
        array_splice($GLOBALS['table_definition']['columns'], 3, 0, [
          [
            'name' => TABLE_HEADING_DISPLAY_GROUP,
            'class' => 'text-end',
            'function' => function ($row) {
              return $row['display_group'];
            },
          ],
        ]);
      }
    }

  }
