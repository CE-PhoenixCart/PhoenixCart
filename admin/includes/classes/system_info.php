<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class system_info {

    protected $data = [];

    public function __construct() {
      $mysql = $GLOBALS['db']->query(<<<'EOSQL'
SELECT
 VERSION() AS `version`,
 NOW() AS `datetime`,
 TIME_FORMAT(TIMEDIFF(NOW(), UTC_TIMESTAMP),'%H:%i') AS `offset`,
 IF(@@session.time_zone = 'SYSTEM',
    @@system_time_zone,
    @@session.time_zone
   ) AS `time_zone`
EOSQL
        )->fetch_assoc();

      $this->data['phoenix'] = ['version' => Versions::get('Phoenix')];

      $this->data['system'] = [
        'date' => date('Y-m-d H:i:s O T'),
        'os' => PHP_OS,
        'kernel' => preg_split('{[\s,]+}', @exec('uname -a'), 5)[2] ?? '',
        'uptime' => @exec('uptime'),
        'http_server' => $_SERVER['SERVER_SOFTWARE'],
      ];

      $this->data['mysql'] = [
        'version' => $mysql['version'],
        'server_info' => $GLOBALS['db']->server_info,
        'date' => $mysql['datetime'],
        'offset' => $mysql['offset'],
        'time_zone' => $mysql['time_zone'],
      ];

      $this->data['php'] = [
        'version' => PHP_VERSION,
        'zend' => zend_version(),
        'sapi' => PHP_SAPI,
        'int_size'	=> defined('PHP_INT_SIZE') ? PHP_INT_SIZE : '',
        'open_basedir' => (int) @ini_get('open_basedir'),
        'memory_limit' => @ini_get('memory_limit'),
        'error_reporting' => error_reporting(),
        'display_errors' => (int) @ini_get('display_errors'),
        'allow_url_fopen' => (int) @ini_get('allow_url_fopen'),
        'allow_url_include' => (int) @ini_get('allow_url_include'),
        'file_uploads' => (int) @ini_get('file_uploads'),
        'upload_max_filesize' => @ini_get('upload_max_filesize'),
        'post_max_size' => @ini_get('post_max_size'),
        'disable_functions' => @ini_get('disable_functions'),
        'disable_classes' => @ini_get('disable_classes'),
        'enable_dl'	=> (int) @ini_get('enable_dl'),
        'filter.default'   => @ini_get('filter.default'),
        'zend.ze1_compatibility_mode' => (int) @ini_get('zend.ze1_compatibility_mode'),
        'unicode.semantics' => (int) @ini_get('unicode.semantics'),
        'zend_thread_safty'	=> (int) function_exists('zend_thread_id'),
        'extensions' => get_loaded_extensions(),
      ];
    }

    /**
     *
     * @param string $section
     */
    public function add_section(string $section) {
      if (!isset($this->data[$section])) {
        $this->data[$section] = [];
      }
    }

    /**
     *
     * @param string $section
     * @param string $key
     * @return mixed
     */
    public function get(string $section, string $key) {
      return $this->data[$section][$key];
    }

    /**
     *
     * @param string $section
     * @return mixed
     */
    public function get_section(string $section) {
      return $this->data[$section];
    }

    /**
     *
     * @return array
     */
    public function get_data() {
      return $this->data;
    }

    /**
     *
     * @param string $section
     * @param string $key
     * @return bool
     */
    public function has(string $section, string $key = null) {
      if (is_null($key)) {
        return isset($this->data[$section]);
      }

      return isset($this->data[$section][$key]);
    }

    /**
     *
     * @param string $section
     * @param string $key
     * @param mixed $value
     */
    public function set(string $section, string $key, $value) {
      $this->data[$section][$key] = $value;
    }

    /**
     *
     * @return string
     */
    public function __toString() {
      $output = '';
      foreach ($this->data as $section => $child) {
        $output .= '[' . $section . ']' . PHP_EOL;
        foreach ($child as $variable => $value) {
          $output .= "$variable = "
                   . (is_array($value) ? implode(', ', $value) : $value)
                   . PHP_EOL;
        }

        $output .= PHP_EOL;
      }

      return $output;
    }

  }
