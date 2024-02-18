<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class sc_github_directory {
    
    public $title;
    public $type = 'warning';

    public function __construct() {
      $this->title = MODULE_SECURITY_CHECK_GITHUB_TITLE;
    }

    public function pass() {
      return !file_exists(DIR_FS_CATALOG . '.github');
    }

    public function get_message() {
      return MODULE_SECURITY_CHECK_GITHUB_DIRECTORY_EXISTS;
    }
    
  }
