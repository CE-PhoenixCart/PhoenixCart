<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class sc_config_file_catalog {

    public $type = 'warning';

    public function pass() {
      return (file_exists(DIR_FS_CATALOG . 'includes/configure.php') && !File::is_writable(DIR_FS_CATALOG . 'includes/configure.php'));
    }

    public function get_message() {
      return WARNING_CONFIG_FILE_WRITEABLE;
    }

  }
