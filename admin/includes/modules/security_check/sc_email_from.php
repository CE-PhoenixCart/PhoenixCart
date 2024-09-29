<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  class sc_email_from {
    
    public $title;
    public $type = 'error';
    public $has_doc = false;

    public function __construct() {
      $this->title = MODULE_SECURITY_CHECK_EMAIL_FROM_TITLE;
    }

    public function pass() {
      return ('root@localhost' !== EMAIL_FROM);
    }

    public function get_message() {
      return MODULE_SECURITY_CHECK_EMAIL_FROM_ERROR;
    }

  }
