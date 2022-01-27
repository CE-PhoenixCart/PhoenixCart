<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class asce_admin_backup_file {

    public $type = 'error';
    public $has_doc = true;
    protected $curl_handle = null;
    protected $url;

    public function __construct($security_checks) {
      $this->title = MODULE_SECURITY_CHECK_EXTENDED_ADMIN_BACKUP_FILE_TITLE;

      if ( is_dir(DIR_FS_BACKUP) ) {
        $dir = dir(DIR_FS_BACKUP);
        $contents = [];
        while ($file = $dir->read()) {
          if ( !is_dir(DIR_FS_BACKUP . $file) ) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            if ( in_array($ext, ['zip', 'sql', 'gz']) && !isset($contents[$ext]) ) {
              $contents[$ext] = $file;

              if ( $ext !== 'sql' ) { // zip and gz (binaries) are prioritized over sql (plain text)
                break;
              }
            }
          }
        }

        $backup_file = $contents['zip'] ?? $contents['gz'] ?? $contents['sql'] ?? null;

        $this->url = $GLOBALS['Admin']->link("backups/$backup_file");
        $this->curl_handle = $security_checks->fetch_curl_handle($this->url);
        if (!$security_checks->add_curl_handle($this->curl_handle, __CLASS__)) {
          $this->curl_handle = null;
        }
      }
    }

    public function pass($request) {
      return is_null($this->curl_handle) || ($request['http_code'] != 200);
    }

    public function get_message() {
      return sprintf(
        MODULE_SECURITY_CHECK_EXTENDED_ADMIN_BACKUP_FILE_HTTP_200,
        DIR_WS_ADMIN . 'backups/');
    }

    public function close() {
      curl_close($this->curl_handle);
    }

  }
