<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class sce_shopside_phoenix {

    public $title;
    public $type = 'info';
    public $has_doc = false;

    public function __construct() {
      $this->title = MODULE_SECURITY_CHECK_EXTENDED_SHOPSIDE_PHOENIX_TITLE;
    }

    public function pass() {
      return false;
    }

    public function get_message() {
      return sprintf(
        MODULE_SECURITY_CHECK_EXTENDED_SHOPSIDE_PHOENIX_MESSAGE,
        Versions::get('Phoenix'),
        $GLOBALS['Admin']->link('version_check.php'));
    }

  }
