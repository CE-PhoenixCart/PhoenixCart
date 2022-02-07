<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class sc_session_storage {

    public $type = 'warning';

    public function pass() {
      return (!defined('DIR_FS_SESSION') || !DIR_FS_SESSION || (is_dir(DIR_FS_SESSION) && is_writable(DIR_FS_SESSION)));
    }

    public function get_message() {
      if (defined('DIR_FS_SESSION') && DIR_FS_SESSION) {
        if (!is_dir(DIR_FS_SESSION)) {
          return sprintf(WARNING_SESSION_DIRECTORY_NON_EXISTENT, session_save_path());
        }

        if (!is_writable(DIR_FS_SESSION)) {
          return sprintf(WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, session_save_path());
        }
      }
    }

  }
