<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Linker {

    protected $prefix;

    public function __construct($prefix = HTTP_SERVER . DIR_WS_CATALOG) {
      $this->prefix = $prefix;
    }

    public function get_prefix() {
      return $this->prefix;
    }

    public function set_prefix($prefix) {
      $this->prefix = $prefix;
    }

    public function build($page = null, $parameters = [], $add_session_id = true) {
      return new Href($this->prefix, $page, $parameters, $add_session_id);
    }

  }
