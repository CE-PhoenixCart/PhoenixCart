<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class File {

    /**
     * Windows compatibility function.
     * @param string $file
     * @return boolean
     */
    public static function is_writable(string $file) {
      if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
        return is_writable($file);
      }

      if (file_exists($file)) {
        $file = realpath($file);
        if (!is_dir($file)) {
          $handle = @fopen($file, 'r+');
          if (is_resource($handle)) {
            fclose($handle);
            return true;
          }
        }
      }

      return false;
    }

    /**
     *
     * @param string $file
     * @return boolean
     */
    public static function remove(string $file) {
      return static::is_writable($file) && unlink($file);
    }

  }
