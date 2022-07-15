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

    public static function list_modules() {
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

          if ($module->base_constant('CONTENT_WIDTH') && !isset($installed_modules[$key]['content_width'])) {
            $installed_modules[$key]['content_width'] = $module->base_constant('CONTENT_WIDTH');
          }
        } else {
          $key = "{$page}-{$module->title}-" . count($new_modules);

          $new_modules[$key] = get_object_vars($module);
          $new_modules[$key]['status'] = $module->check();
          $new_modules[$key]['group'] = $page;
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
        array_splice($GLOBALS['table_definition']['columns'], 2, 0, [
          [
            'name' => TABLE_HEADING_WIDTH,
            'function' => function ($row) {
              return $row['content_width'];
            },
          ],
        ]);

        array_splice($GLOBALS['table_definition']['columns'], -1, 0, [
          [
            'name' => TABLE_HEADING_ENABLED,
            'class' => 'text-right',
            'function' => function ($row) {
              return ($row['status'] > 0)
                   ? '<i class="fas fa-check-circle text-success"></i>'
                   : '<i class="fas fa-times-circle text-danger"></i>';
            },
          ],
        ]);
      }
    }

  }
