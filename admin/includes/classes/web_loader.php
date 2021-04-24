<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  class web_loader {

    protected $components;
    protected $hostname;
    protected $protocol = 'HTTP/1.0';
    protected $error_number;
    protected $error_message;

    public function __construct($url) {
      if (is_array($url)) {
        $this->components = $url;
      } elseif (is_string($url)) {
        $this->components = parse_url($url);
      }
    }

    protected function extract_port() {
      if (isset($this->components['port'])) {
        return;
      }

      switch ($this->components['scheme']) {
        case 'https':
          if (extension_loaded('openssl')) {
            $this->hostname = "ssl://{$this->components['host']}";
            $this->components['port'] = 443;
            break;
          }
        case 'http':
          $this->hostname = $this->components['host'];
          $this->components['port'] = 80;
          break;
        default:
          $this->hostname = $this->components['host'];
          $this->components['port'] = -1;
      }
    }

    public function get($key) {
      return $this->components[$key];
    }

    public function load() {
      if (empty($this->components)) {
        error_log('Cannot load an empty URL');
        return;
      }

      static::extract_port($this->components);
      if ($fp = fsockopen($this->hostname, $this->components['port'], $this->error_number, $this->error_message, 30)) {
        $result = fwrite($fp,
            "GET {$this->components['path']} {$this->protocol}\r\n"
          . "Host: {$this->components['host']}\r\n"
          . "Connection: close\r\n\r\n");

        if (!$result) {
          error_log("Could not write to URL");
          return;
        }

        if (feof($fp)) {
          error_log("No response from URL");
          return;
        }

        $response = '';
        do {
          $response .= fgets($fp, 1024);
        } while (!feof($fp));
        fclose($fp);

// we don't need the header from the response
// so strip everything before the first empty line
        $response = explode("\r\n\r\n", $response, 2);
        if (empty($response[1])) {
          return '';
        }

        return ($end_of_first_line = strpos($response[1], "\r\n"))
             ? substr($response[1], $end_of_first_line + strlen("\r\n"))
             : $response[1];
      } elseif (isset($this->error_number)) {
        error_log("Could not open '{$this->components['host']}' [{$this->error_number}]:  '" . ($this->error_message ?? '') ."'");
      }
    }

    public function set($key, $value) {
      $this->components[$key] = $value;
      return $this;
    }

    public function set_hostname($hostname) {
      $this->hostname = $hostname;
      return $this;
    }

    public function set_protocol($protocol) {
      $this->protocol = $protocol;
      return $this;
    }

  }
