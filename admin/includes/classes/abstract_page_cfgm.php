<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  abstract class abstract_page_cfgm {

    const LANGUAGE_DIRECTORY = DIR_FS_CATALOG . 'includes/languages/';
    const TEMPLATE_INTEGRATION = false;

    public static function fix_installed_constant(&$installed_modules) {
      return empty($_GET['page']);
    }

    public static function generate_pages() {
      if (!($maindir = @dir(static::DIRECTORY))) {
        return;
      }

      while ($page = $maindir->read()) {
        if (($page[0] !== '.') && is_dir(static::DIRECTORY . $page)) {
          yield $page;
        }
      }

      $maindir->close();
    }

    public static function generate_modules_for(string $page) {
      $path = static::DIRECTORY . $page;
      if ( !is_dir($path) || !($dir = @dir($path))) {
        return;
      }

      while ($file = $dir->read()) {
        if (!is_dir("$path/$file")) {
          yield $page => $file;
        }
      }

      $dir->close();
    }

    public static function generate_modules() {
      if (empty($_GET['page'])) {
        foreach (static::generate_pages() as $page) {
          yield from static::generate_modules_for($page);
        }
      } else {
        yield from static::generate_modules_for(Text::input($_GET['page']));
      }
    }

    public static function list_page_options() {
      return array_merge(
        [['id' => '', 'text' => TEXT_ALL_MODULES]],
        array_map(function ($page) {
          return ['id' => $page, 'text' => $page];
        }, iterator_to_array(static::generate_pages())));
    }

    public static function menu() {
      $form = new Form('choose_page', $GLOBALS['link'], 'get');
      foreach (array_diff_key($GLOBALS['link']->get_parameters(), ['page' => 0]) as $k => $v) {
        if (is_string($v) && (strlen($v) > 0)) {
          $form->hide($k, $v);
        }
      }

      return $form
           . new Select('page', static::list_page_options(), ['class' => 'form-select', 'onchange' => 'this.form.submit();'])
           . '</form>';
    }

  }
