<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class hooks {

    protected $_site;
    protected $_hooks = [];
    const PREFIX = 'listen_';
    protected $prefix_length;
    protected $pipelines = [];
    protected $page;
    protected $hook_directories = [];

    public function __construct($site) {
      $this->_site = basename($site);
      $this->prefix_length = strlen(self::PREFIX);
      $this->add_directory(DIR_FS_CATALOG . 'includes/hooks/');
    }

    public function add_directory($directory) {
      $this->hook_directories[] = $directory . $this->_site . '/';
    }

    protected function sort_hooks() {
      foreach ( $this->_hooks as &$actions ) {
        foreach ( $actions as &$codes ) {
          uksort($codes, 'strnatcmp');
        }
      }
    }

    protected function build_callback($class, $method) {
      if ('' === $class) {
        return $method;
      }

      if (isset($_SESSION[$class]) && is_callable([$_SESSION[$class], $method])) {
        return [$_SESSION[$class], $method];
      }

      if (!class_exists($class)) {
        return null;
      }

      if (is_callable([$class, $method])) {
        $m = new \ReflectionMethod($class, $method);
        if ($m->isStatic()) {
          return [$class, $method];
        }
      }

      return [Guarantor::ensure_global($class), $method];
    }

    protected function load($group) {
      $hooks_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT hooks_action, hooks_code, hooks_class, hooks_method
 FROM hooks
 WHERE hooks_site = '%s' AND hooks_group = '%s'
EOSQL
, $GLOBALS['db']->escape($this->_site), $GLOBALS['db']->escape($group)));

      while ($hook = $hooks_query->fetch_assoc()) {
        $callback = $this->build_callback($hook['hooks_class'], $hook['hooks_method']);
        if (is_callable($callback)) {
          Guarantor::guarantee_all(
            $this->_hooks,
            $this->_site,
            $hook['hooks_action']
          )[$hook['hooks_code']] = $callback;
        }
      }

      $this->sort_hooks();
    }

    protected function register_directory($directory, &$files) {
      if ( file_exists($directory) && ( $dir = @dir($directory) ) ) {
        while ( $file = $dir->read() ) {
          if ( !is_dir("$directory/$file") ) {
            $files[] = $file;
          }
        }

        $dir->close();
      }
    }

    public function register($group) {
      $group = basename($group);

      $files = [];
      foreach ($this->hook_directories as $directory) {
        $this->register_directory("$directory$group", $files);
      }

      foreach ($files as $file) {
        $pathinfo = pathinfo($file);
        if ( 'php' === $pathinfo['extension'] ) {
          $class = "hook_{$this->_site}_{$group}_{$pathinfo['filename']}";

          foreach ( get_class_methods(Guarantor::ensure_global($class)) as $method ) {
            if ( substr($method, 0, $this->prefix_length) === self::PREFIX ) {
              $action = substr($method, $this->prefix_length);
              Guarantor::guarantee_all($this->_hooks, $this->_site, $action
                )[$pathinfo['filename']] = [$GLOBALS[$class], $method];
            }
          }
        }
      }

      $this->load($group);
    }

    public function register_page() {
      $this->page = pathinfo($GLOBALS['PHP_SELF'], PATHINFO_FILENAME);
      $this->register($this->page);
      $this->register_pipeline('siteWide');
      $this->call('siteWide', 'injectAppTop');
    }

    public function register_pipeline($pipeline, &$parameters = null) {
      $this->register($pipeline);
      $this->call($this->page, "{$pipeline}Start", $parameters);
    }

    public function set($action, $code, $callable) {
      $hooks =& Guarantor::guarantee_all($this->_hooks, $this->_site, $action);
      $hooks[$code] = $callable;

      uksort($hooks, 'strnatcmp');
    }

    public function call($group, $action, $parameters = []) {
      return $this->cat($action, $parameters);
    }

    public function cat($action, $parameters = []) {
      $result = '';
      foreach ( @(array)$this->_hooks[$this->_site][$action] as $callback ) {
        $result .= call_user_func($callback, $parameters);
      }

      if ( $result ) {
        return $result;
      }
    }

    public function generate($action, $parameters = []) {
      foreach ( @(array)$this->_hooks[$this->_site][$action] as $callback ) {
        yield call_user_func($callback, $parameters);
      }
    }

    public function chain($action, $parameters = []) {
      foreach ( @(array)$this->_hooks[$this->_site][$action] as $callback ) {
        $parameters = call_user_func($callback, $parameters);
      }

      return $parameters;
    }

    public function get_hook_directories() {
      return $this->hook_directories;
    }

  }
