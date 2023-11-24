<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class Href implements \JsonSerializable {

    protected $include_session = true;
    protected $page;
    protected $parameters = [];
    protected $separator_encoding = true;
    protected $hooks = null;

    public function __construct($prefix = '', $page = null, $parameters = [], $add_session_id = true) {
      $this->page = Text::output($prefix . ($page ?? Request::get_page()));

      if (is_array($parameters)) {
        $this->parameters = $parameters;
      } elseif (!Text::is_empty($parameters)) {
        parse_str($parameters, $this->parameters);
      }

      $this->set_include_session($add_session_id);
    }

    public function set_hooks(&$hooks) {
      $this->hooks =& $hooks;
      return $this;
    }

    public function set_include_session(bool $include_session) {
      $this->include_session = $include_session && (SESSION_FORCE_COOKIE_USE === 'False');
      return $this;
    }

    public function set_page($page) {
      $this->page = $page;
      return $this;
    }

    public function set_parameter(string $name, string $value) {
      $this->parameters[$name] = $value;
      return $this;
    }

    public function set_separator_encoding($separator_encoding) {
      $this->separator_encoding = $separator_encoding;
      return $this;
    }

    public function add_parameters(array $parameters) {
      $this->parameters += $parameters;
      return $this;
    }

    public function delete_parameter($name) {
      unset($this->parameters[$name]);
      return $this;
    }

    public function get_include_session() {
      return $this->include_session;
    }

    public function get_page() {
      return $this->page;
    }

    public function &get_parameters() {
      return $this->parameters;
    }

    public function get_separator_encoding() {
      return $this->separator_encoding;
    }

    public function retain_parameters(array $excludes = []) {
      trigger_error('The retain_parameters method has been deprecated.', E_USER_DEPRECATED);
      return $this->retain_query_except($excludes);
    }

    public function retain_query_except(array $excludes = []) {
      $excludes = array_merge($excludes, ['x', 'y', 'error', session_name()]);
      static::_flatten(array_diff_key(array_filter($_GET, function ($k) {
        return rawurlencode($k) === $k;
      }, ARRAY_FILTER_USE_KEY), array_flip($excludes)), $this->parameters);
      return $this;
    }

    public function real_link() {
      if (Text::is_empty($this->page)) {
        die('<h5>Error!</h5><p>Unable to determine the page link!</p>');
      }

// Add the session ID when SID is defined
      if ( $this->include_session
        && Session::is_started()
        && isset($GLOBALS['SID'])
        && !Text::is_empty($GLOBALS['SID']))
      {
        $this->parameters[session_name()] = session_id();
      }

      $parameters = implode('&', array_map(function ($k, $v) {
        return "$k=" . rawurlencode("$v");
      }, array_keys($this->parameters), $this->parameters));

      $link = $this->page;
      if (!Text::is_empty($parameters)) {
        $link .= '?' . Text::output($parameters);
      }

      $link = rtrim($link, '&?');
      while (strpos($link, '&&') !== false) {
        $link = str_replace('&&', '&', $link);
      }

      if ($this->separator_encoding) {
        $link = str_replace('&', '&amp;', $link);
      }

      return $link;
    }

    public function link() {
      $chain = [
        'link' => &$link,
        'href' => $this,
      ];
      $chain = ($this->hooks ?? $GLOBALS['all_hooks'])->chain('hrefLink', $chain);
      return $chain['link'] ?? $this->real_link();
    }

    public function __toString() {
      return $this->link();
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize() {
      return "$this";
    }

    public static function hook($chain) {
      if (isset($chain['href'])) {
        $chain['link'] = $chain['href']->real_link();
      }

      return $chain;
    }

    public static function build(...$arguments) {
      return new static(...$arguments);
    }

    protected static function _build_prefixed_key(string $key, string $prefix = null) {
      return is_null($prefix)
           ? $key
           : sprintf('%s[%s]', $prefix, rawurlencode($key));
    }

    protected static function _flatten(
      array $data,
      array &$results,
      string $prefix = null
    ) {
      foreach ($data as $key => $value) {
        $key = static::_build_prefixed_key($key, $prefix);
        if (is_array($value)) {
          static::_flatten($value, $results, $key);
        } else {
          $results[$key] = $value;
        }
      }
    }

    public static function redirect($url) {
      if ($url instanceof Href) {
        $url = $url->set_separator_encoding(false)->link();
      }

      if ( strstr($url, "\n") || strstr($url, "\r") ) {
        static::redirect(static::build('index.php')->set_include_session(false)->link());
      }

      if ( strpos($url, '&amp;') !== false ) {
        $url = str_replace('&amp;', '&', $url);
      }

      header('Location: ' . $url);
      exit();
    }

  }
