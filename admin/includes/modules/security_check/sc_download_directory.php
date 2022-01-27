<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class sc_download_directory {

    const FS_DOWNLOAD_DIRECTORY = DIR_FS_CATALOG . 'download/';

    public $type = 'warning';

    public function pass() {
      return ('true' !== DOWNLOAD_ENABLED) || is_dir(static::FS_DOWNLOAD_DIRECTORY);
    }

    public function get_message() {
      return sprintf(WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, static::FS_DOWNLOAD_DIRECTORY);
    }

  }
