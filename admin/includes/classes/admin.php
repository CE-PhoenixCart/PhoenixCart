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
    protected $processor = null;

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

    public function set_processor($processor = null) {
      $this->processor = $processor;
    }

    public function link($page = null, $parameters = [], $add_session_id = true) {
      return $this->linker->build($page, $parameters, $add_session_id);
    }

    public function catalog($page = null, $parameters = []) {
      return $this->catalog_linker->build($page, $parameters, false);
    }

    public static function button($text, $icon,
      $style = 'btn-outline-secondary', $link = false, $parameters = [])
    {
      return $link ? new Button($text, $icon, $style, $parameters, $link) : '';
    }

    public static function camel_case(string $snake_case) {
      return lcfirst(implode('',
        array_map('ucfirst', explode('_', $snake_case))));
    }

    public static function catalog_image($image, ...$arguments) {
      if (Text::is_empty($image) || !file_exists(DIR_FS_CATALOG . "images/$image") ) {
        return TEXT_IMAGE_NON_EXISTENT;
      }

      return (new Image("images/$image", ...$arguments))
        ->set_web_prefix(HTTP_SERVER . DIR_WS_CATALOG)
        ->set_default(false);
    }

    public static function image(...$arguments) {
      return (new Image(...$arguments))->set_prefix(DIR_FS_ADMIN);
    }

    public function locate_action($action) {
      if (empty($action) || (!Form::validate_action_is($action)
        && !in_array($action, $GLOBALS['always_valid_actions'])))
      {
        return;
      }

      $page = $this->processor
           ?? pathinfo(Request::get_page(), PATHINFO_FILENAME);

      $d = rtrim(realpath(DIR_FS_ADMIN . "includes/actions/$page"),
             DIRECTORY_SEPARATOR);
      if (!is_dir($d)) {
        return;
      }

      $f = realpath("$d/$action.php");
      if ((dirname($f) === $d) && is_file($f)) {
        return $f;
      }
    }

  }
