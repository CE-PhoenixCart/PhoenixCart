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
    protected $processor;
    protected $action_directory;

    public function __construct($processor = null) {
      $this->linker = new Linker(HTTP_SERVER . DIR_WS_ADMIN);
      $this->catalog_linker = new Linker(HTTP_CATALOG_SERVER . DIR_WS_CATALOG);
      $this->set_processor($processor
        ?? pathinfo(Request::get_page(DIR_WS_ADMIN), PATHINFO_FILENAME));
    }

    public function get_linker() {
      return $this->linker;
    }

    public function get_catalog_linker() {
      return $this->catalog_linker;
    }

    public function set_processor(string $processor = null) {
      $this->processor = $processor;
      $this->action_directory = rtrim(
        Path::normalize(DIR_FS_ADMIN . "includes/actions/{$this->processor}"),
        '\/');
    }

    public function link($page = null, $parameters = [], $add_session_id = true) {
      return $this->linker->build($page, $parameters, $add_session_id);
    }

    public function relink($url) {
      $pos_params = strpos($url, '?', 0);
      if (false === $pos_params) {
        return $GLOBALS['Admin']->link($url);
      }

      parse_str(substr($url, $pos_params + 1), $parameters);
      return $GLOBALS['Admin']->link(substr($url, 0, $pos_params), $parameters);
    }

    public function catalog($page = null, $parameters = []) {
      static $hooks = null;

      if (is_null($hooks)) {
        $hooks = &Guarantor::ensure_global('hooks', 'shop');
        $hooks->register('system');
        $hooks->register_pipeline('siteWide');
      }

      return $this->catalog_linker->build($page, $parameters, false)->set_hooks($hooks);
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
      if (Text::is_empty($image) || !is_file(DIR_FS_CATALOG . $image) ) {
        return TEXT_IMAGE_NON_EXISTENT;
      }

      return (new Image($image, ...$arguments))
        ->set_web_prefix(HTTP_CATALOG_SERVER . DIR_WS_CATALOG)
        ->set_default(false);
    }

    public static function image(...$arguments) {
      return (new Image(...$arguments))->set_prefix(DIR_FS_ADMIN);
    }

    public function locate($subdirectory, $action) {
      if (!is_dir($d = "{$this->action_directory}$subdirectory")) {
        return;
      }

      $f = Path::normalize("$d/$action.php");
      if (($f || ($f = Path::normalize("$d/default.php")))
        && (dirname($f) === $d))
      {
        return $f;
      }
    }

    public function locate_action($action) {
      if ( $action
        && !in_array($action, $GLOBALS['always_valid_actions'] ?? [])
        && !Form::validate_action_is($action) )
      {
        return $this->locate('', 'default');
      }

      return $this->locate('', $action);
    }

  }
