<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class asce_admin_backup_directory_listing {

    public $type = 'error';
    public $has_doc = true;
    protected $curl_handle;
    protected $url;

    public function __construct($security_checks) {
      $this->title = MODULE_SECURITY_CHECK_EXTENDED_ADMIN_BACKUP_DIRECTORY_LISTING_TITLE;

      $this->url = $GLOBALS['Admin']->link('backups/');
      $this->curl_handle = $security_checks->fetch_curl_handle($this->url);
      if (!$security_checks->add_curl_handle($this->curl_handle, __CLASS__)) {
        $this->curl_handle = null;
      }
    }

    public function pass($request) {
      return $request['http_code'] != 200;
    }

    public function get_message() {
      return sprintf(
        MODULE_SECURITY_CHECK_EXTENDED_ADMIN_BACKUP_DIRECTORY_LISTING_HTTP_200,
        $this->url,
        DIR_WS_ADMIN . 'backups/');
    }

    public function close() {
      curl_close($this->curl_handle);
    }

  }
