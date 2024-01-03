<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class sce_admin_http_authentication {

    public $title;
    public $type = 'warning';

    public function __construct() {
      $this->title = MODULE_SECURITY_CHECK_EXTENDED_ADMIN_HTTP_AUTHENTICATION_TITLE;
    }

    public function pass() {
      $is_iis = stripos($_SERVER['SERVER_SOFTWARE'], 'iis');
      
      $htaccess_path = DIR_FS_ADMIN . '.htaccess';
      $htpasswd_path = DIR_FS_ADMIN . '.htpasswd_phoenix';
      
      $htaccess_lines = [];
      if (!$is_iis && file_exists($htpasswd_path) && Path::is_writable($htpasswd_path) && file_exists($htaccess_path) && Path::is_writable($htaccess_path)) {
        if (filesize($htaccess_path) > 0) {
          $htaccess_lines = explode("\n", file_get_contents($htaccess_path));
        }

        $htpasswd_lines = (filesize($htpasswd_path) > 0) ? explode("\n", file_get_contents($htpasswd_path)) : [];
      } else {
        $htpasswd_lines = false;
      }
      
      if (is_array($htpasswd_lines)) {
        if (empty($htpasswd_lines)) {
          return false;
        }
        
        return true;
      }
      else if (!$is_iis) {
        return false;
      }      
    }

    public function get_message() {
      return MODULE_SECURITY_CHECK_EXTENDED_ADMIN_HTTP_AUTHENTICATION_ERROR;
    }

  }
