<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class sc_install_directory {

    public $type = 'warning';

    public function pass() {
      return !file_exists(DIR_FS_CATALOG . 'install');
    }

    public function get_message() {
      return WARNING_INSTALL_DIRECTORY_EXISTS;
    }

  }
