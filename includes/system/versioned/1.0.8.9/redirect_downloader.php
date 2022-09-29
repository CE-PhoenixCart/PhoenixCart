<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class redirect_downloader {

// Unlinks all subdirectories and files in $dir
// Works only on one subdir level, will not recurse
    protected static function unlink_temp_dir(string $dir) {
      foreach (array_diff(scandir($dir), ['.', '..']) as $subdir) {
// Ignore . and .. and non directories
        if (!is_dir("$dir$subdir")) {
          continue;
        }

// Loop and unlink files in subdirectory
        $subpath = "$dir$subdir";
        foreach (array_diff(scandir($subpath), ['.', '..']) as $file) {
          @unlink("$subpath/$file");
        }

        @rmdir($subpath);
      }
    }

/**
 * Returns a random name, 16 to 20 characters long
 * There are more than 10^28 combinations
 * The directory is "hidden", i.e. starts with '.'
 *
 * @return string
 */
    protected static function create_random_name() {
      $letters = 'abcdefghijklmnopqrstuvwxyz';

      $dirname = '.';
      $length = mt_rand(16, 20);
      while (strlen($dirname) < $length) {
        $dirname .= $letters[random_int(0, 25)];
      }

      return $dirname;
    }

/**
 * Redirect to a temporary symbol link to the file.
 * This will work only on Unix/Linux hosts and may be blocked by security restrictions.
 * If not allowed, turn DOWNLOAD_BY_REDIRECT to false.
 *
 * @param string $path
 * @param string $filename
 */
    public static function link(string $path, string $filename) {
      static::unlink_temp_dir('pub/');
      $tempdir = static::create_random_name();

      mkdir("pub/$tempdir", 0777);
      $file = "pub/$tempdir/$filename";
      symlink($path, $file);
      if (file_exists($file)) {
        Href::redirect($GLOBALS['Linker']->build($file, [], false));
      }
    }

  }
