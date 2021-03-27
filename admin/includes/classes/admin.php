<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Admin {

    protected $linker;
    protected $catalog_linker;

    public function __construct() {
      $this->linker = new Linker(HTTP_SERVER . DIR_WS_ADMIN);
      $this->catalog_linker = new Linker(HTTP_CATALOG_SERVER . DIR_WS_CATALOG);
    }

    public function get_linker() {
      return $this->linker;
    }

    public function get_catalog_linker() {
      return $this->catalog_linker;
    }

    public function link($page = null, $parameters = [], $add_session_id = true) {
      return $this->linker->build($page, $parameters, $add_session_id);
    }

    public function catalog($page = null, $parameters = []) {
      return $this->catalog_linker->build($page, $parameters, false);
    }

    public static function image(...$arguments) {
      return (new Image(...$arguments))->set_prefix(DIR_FS_ADMIN);
    }

  }
