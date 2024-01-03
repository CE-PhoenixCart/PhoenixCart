<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class sce_version_check {

    const URL = 'https://github.com/CE-PhoenixCart/PhoenixCart/blob/master/includes/version.php';

    public $title;
    public $type = 'warning';
    public $has_doc = true;
    protected $version;

    public function __construct() {
      $this->title = MODULE_SECURITY_CHECK_EXTENDED_VERSION_CHECK_TITLE;
      $this->version = trim(Web::load(static::URL));
    }

    public function pass() {
      return !version_compare(Versions::get('Phoenix'), $this->version, '<');
    }

    public function get_message() {
      return '<a href="' . $GLOBALS['Admin']->link('version_check.php') . '">' . MODULE_SECURITY_CHECK_EXTENDED_VERSION_CHECK_ERROR . '</a>';
    }

  }
