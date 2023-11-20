<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class page_selection {

    public function __construct() {}

    public static function _get_pages($p = '') {
      return array_filter(array_map('trim', explode(';', $p)));
    }

    public static function _show_pages($text) {
      return abstract_module::list_exploded($text);
    }

    public static function _edit_pages($values, $key) {
      $files = [];

      // main files
      foreach (new DirectoryIterator(DIR_FS_CATALOG) as $file) {
        if ($file->isFile() && ($file->getExtension() === 'php')) {
          $files[] = $file->getFilename();
        }
      }

      // ext files
      $dir = new RecursiveDirectoryIterator(DIR_FS_CATALOG . 'ext/modules/content/');
      $iterator = new RecursiveIteratorIterator($dir);

      foreach ($iterator as $file) {
        if ($file->isFile() && ($file->getExtension() === 'php')) {
          $files[] = $file->getFilename();
        }
      }

      $files = array_unique($files);
      sort($files);

      $output = Config::select_multiple($files, $values, $key) . '<br>'
              . new Tickable('p_all', ['class' => ' '], 'checkbox') . '&nbsp;' . TEXT_ALL;

      $key_name = Config::name($key) . '[]';
      $output .= <<<"EOSCRIPT"
<script>
  $('input[name="p_all"]').click(function() {
    $('input[name="$key_name"]').prop('checked', $(this).prop('checked'));
  });
  $('input[name="$key_name"]').click(function() {
    if (!$(this).prop('checked')) {
      $('input[name="p_all"]').prop('checked', false);
    }
  });
</script>

EOSCRIPT;

      return $output;
    }

  }
