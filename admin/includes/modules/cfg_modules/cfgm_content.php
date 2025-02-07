<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class cfgm_content extends abstract_page_cfgm {

    const CODE = 'content';
    const DIRECTORY = DIR_FS_CATALOG . 'includes/modules/content/';
    const KEY = 'MODULE_CONTENT_INSTALLED';
    const TITLE = MODULE_CFG_MODULE_CONTENT_TITLE;
    
    const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Content';
    const GET_ADDONS_LINKS = [ADDONS_FREE => 'https://phoenixcart.org/forum/app.php/addons/free/content-25',
                              ADDONS_COMMERCIAL => 'https://phoenixcart.org/forum/app.php/addons/commercial/content-33',
                              ADDONS_PRO => 'https://phoenixcart.org/forum/app.php/addons/supporters/content-43',];

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

          if (is_callable([$module, 'get_group']) && !isset($installed_modules[$key]['group'])) {
            $installed_modules[$key]['group'] = $module->get_group();
          }
          $installed_modules[$key]['file'] = sprintf('%s/%s',
            $installed_modules[$key]['group'], $installed_modules[$key]['code']);
            
          $installed_modules[$key]['content_width'] = $module->base_constant('CONTENT_WIDTH') ?? 'N/A';
        } else {
          $key = "{$page}-{$module->title}-" . count($new_modules);

          $new_modules[$key] = get_object_vars($module);
          $new_modules[$key]['status'] = $module->check();

          if (is_callable([$module, 'get_group']) && !isset($new_modules[$key]['group'])) {
            $new_modules[$key]['group'] = $module->get_group();
          }
          $new_modules[$key]['file'] = sprintf('%s/%s',
            $new_modules[$key]['group'], $new_modules[$key]['code']);
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
      }
    }

  }
