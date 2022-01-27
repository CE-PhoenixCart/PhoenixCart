<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class sc_default_language {

    public $type = 'error';

    public function pass() {
      return defined('DEFAULT_LANGUAGE');
    }

    public function get_message() {
      return ERROR_NO_DEFAULT_LANGUAGE_DEFINED;
    }

  }
