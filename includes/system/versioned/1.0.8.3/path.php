<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Path {

    /**
     * Make Windows check match Linux.
     * @param string $path
     * @return boolean
     */
    public static function is_writable(string $path) {
      if (strtolower(substr(PHP_OS, 0, 3)) !== 'win') {
        return is_writable($path);
      }

      if (!file_exists($path)) {
        return is_dir($dir = dirname($path)) && Path::is_writable($dir);
      }

      $path = realpath($path);
      if (!is_dir($path)) {
        return File::is_writable($path);
      }

      $file = @tempnam($path, 'phoenix');
      if (is_string($file) && file_exists($file)) {
        unlink($file);
        return dirname($file) === $path;
      }

      return false;
    }

    /**
     * Make Windows paths match Linux.
     * @param string $path
     * @return string
     */
    public static function normalize(string $path) {
      return str_replace('\\', '/', realpath($path));
    }

    /**
     *
     * @param string $path
     * @return boolean
     */
    public static function remove(string $path) {
      if (!is_dir($path)) {
        return File::remove($path);
      }

      if (!static::is_writable($path)) {
        return false;
      }

      $dir = dir($path);
      while ($entry = $dir->read()) {
        if ( ($entry !== '.') && ($entry !== '..') ) {
          $file = "$path/$entry";
          if (!static::is_writable($file) || !static::remove($file)) {
            $dir->close();
            error_log("Failed to remove $path/$file");
            return false;
          }
        }
      }
      $dir->close();

      return rmdir($path);
    }

  }
