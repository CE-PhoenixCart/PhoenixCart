<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  class security_checks {

    const CURL_OPTIONS = [
      CURLOPT_HEADER => false,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FORBID_REUSE => true,
      CURLOPT_FRESH_CONNECT => true,
      CURLOPT_CUSTOMREQUEST => 'HEAD',
      CURLOPT_NOBODY => true,
    ];

    const TYPES = ['info', 'warning', 'error'];

    protected $modules = [];
    protected $curl_handles = [];
    protected $curl_results = [];
    protected $multi_handle;

    public function __construct($extended = false) {
      $this->multi_handle = curl_multi_init();
      if ($extended) {
        $this->find_modules(DIR_FS_ADMIN . 'includes/modules/security_check/extended');
      }
      $this->find_modules(DIR_FS_ADMIN . 'includes/modules/security_check');

      usort($this->modules, function ($a, $b) {
        return strcasecmp($a['title'], $b['title']);
      });
    }

    public function find_modules(string $directory) {
      if ($dir = @dir($directory)) {
        while ($file = $dir->read()) {
          $path = "$directory/$file";
          if (!is_dir($path)
           && ('php' === pathinfo($file, PATHINFO_EXTENSION)))
          {
            $class = pathinfo($file, PATHINFO_FILENAME);

            $GLOBALS[$class] = new $class($this);
            if (!in_array($GLOBALS[$class]->type, static::TYPES)) {
              $GLOBALS[$class]->type = 'info';
            }

            $this->modules[] = [
              'title' => $GLOBALS[$class]->title ?? $class,
              'class' => $class,
            ];
          }
        }

        $dir->close();
      }
    }

    public function generate_modules() {
      if (count($this->curl_handles) > count($this->curl_results)) {
        $this->exec();
      }

      foreach ($this->modules as $module) {
        yield $module;
      }
    }

    /**
     *
     * @param string $url
     * @param array $options
     * @return CurlHandle
     */
    public function fetch_curl_handle(string $url, array $options = []) {
      $server = parse_url($url);

      if (!isset($options[CURLOPT_PORT])) {
        $options[CURLOPT_PORT] = $server['port']
                              ?? (($server['scheme'] === 'https') ? 443 : 80);
      }

      if (!isset($server['path'])) {
        $server['path'] = '/';
      }

      $url = "{$server['scheme']}://{$server['host']}{$server['path']}";
      if (isset($server['query'])) {
        $url .= "?{$server['query']}";
      }

      $curl = curl_init($url);

      $options += static::CURL_OPTIONS;
      curl_setopt_array($curl, $options);

      return $curl;
    }

    public function add_curl_handle($curl, $code) {
      $result = curl_multi_add_handle($this->multi_handle, $curl);
      if (0 === $result) {
        $this->curl_handles[$code] = $curl;
        return true;
      } else {
        error_log("Can't curl_multi_add_handle:  [$result]");
        return false;
      }
    }

    public function exec() {
      do {
        $result = curl_multi_exec($this->multi_handle, $active);
      } while (CURLM_CALL_MULTI_PERFORM === $result);

      do {
        if (in_array(curl_multi_select($this->multi_handle), [0, -1], true)) {
          usleep(100000);
        } else {
          do {
            $result = curl_multi_exec($this->multi_handle, $active);
          } while (CURLM_CALL_MULTI_PERFORM === $result);
        }
      } while ($active && (CURLM_OK === $result));

      while (false !== ($response = curl_multi_info_read($this->multi_handle))) {
        if (false !== ($key = array_search($response['handle'], $this->curl_handles))) {
          $this->curl_results[$key] = curl_getinfo($response['handle']);
          curl_multi_remove_handle($this->multi_handle, $response['handle']);
        }
      }
    }

    public function fetch_curl_result($code) {
      return $this->curl_results[$code] ?? false;
    }

    public function close() {
      array_map(function ($m) {
        $GLOBALS[$m]->close();
      }, array_filter(array_column($this->modules, 'class'), function ($class) {
        return is_callable([$GLOBALS[$class], 'close']);
      }));
    }

  }
