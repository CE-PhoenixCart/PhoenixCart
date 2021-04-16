<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  include DIR_FS_CATALOG . 'includes/apps/paypal/functions/compatibility.php';

  class OSCOM_PayPal {

    public $_code = 'paypal';
    public $_title = 'PayPal App';
    public $_version;
    public $_api_version = '204';
    public $_identifier = 'osCommerce_PPapp_v5';
    public $_definitions = [];

    function log($module, $action, $result, $request, $response, $server, $is_ipn = false) {
      if (!in_array(OSCOM_APP_PAYPAL_LOG_TRANSACTIONS, ['1', '0'])
       || ((OSCOM_APP_PAYPAL_LOG_TRANSACTIONS == '0') && ($result === 1)))
      {
        return false;
      }

      $filter = ['ACCT', 'CVV2', 'ISSUENUMBER'];

      if ( is_array($request) ) {
        $request_string = '';
        foreach ( $request as $key => $value ) {
          if ( (strpos($key, '_nh-dns') !== false) || in_array($key, $filter) ) {
            $value = '**********';
          }

          $request_string .= $key . ': ' . $value . "\n";
        }
      } else {
        $request_string = $request;
      }

      if ( is_array($response) ) {
        $response_string = '';
        foreach ( $response as $key => $value ) {
          if ( is_array($value) ) {
            $value = http_build_query($value);
          } elseif ( (strpos($key, '_nh-dns') !== false) || in_array($key, $filter) ) {
            $value = '**********';
          }

          $response_string .= $key . ': ' . $value . "\n";
        }
      } else {
        $response_string = $response;
      }

      $data = [
        'customers_id' => ($_SESSION['customer_id'] ?? 0),
        'module' => $module,
        'action' => $action . (($is_ipn === true) ? ' [IPN]' : ''),
        'result' => $result,
        'server' => ($server === 'live') ? 1 : -1,
        'request' => trim($request_string),
        'response' => trim($response_string),
        'ip_address' => sprintf('%u', ip2long(Request::get_ip())),
        'date_added' => 'NOW()',
      ];

      $GLOBALS['db']->perform('oscom_app_paypal_log', $data);
    }

    function migrate() {
      $migrated = false;

      foreach ( $this->getModules() as $m ) {
        $key = 'OSCOM_APP_PAYPAL_' . $m . '_STATUS';
        if ( defined($key) ) {
          continue;
        }

        $this->saveParameter($key, '');

        $class = "OSCOM_PayPal_$m";
        if ( !class_exists($class) ) {
          $this->loadLanguageFile("modules/$m/$m.php");

          include DIR_FS_CATALOG . "includes/apps/paypal/modules/$m/$m.php";
        }

        $module = new $class();
        if ( method_exists($module, 'canMigrate') && $module->canMigrate() ) {
          $module->migrate($this);

          if ( $migrated === false ) {
            $migrated = true;
          }
        }
      }

      return $migrated;
    }

    protected function insert_sorted(&$modules, $file) {
      $sort = $this->getModuleInfo($file, 'sort_order');
      $sort = is_numeric($sort) ? (int)$sort : count($modules);
      while ( isset($modules[$sort]) ) {
        $sort++;
      }

      $modules[$sort] = $file;
    }

    public function getModules() {
      static $modules;

      if ( !isset($modules) ) {
        $modules = [];

        $d = DIR_FS_CATALOG . 'includes/apps/paypal/modules/';
        if ( $dir = @dir($d) ) {
          while ( $file = $dir->read() ) {
// skip directories and hidden files
            if ('.' === ($file[0] ?? '')) {
              continue;
            }

            if ( is_dir("$d/$file") && file_exists("$d/$file/$file.php") ) {
              $this->insert_sorted($modules, $file);
            }
          }

          ksort($modules, SORT_NUMERIC);
        }
      }

      return $modules;
    }

    function isInstalled($module) {
      $module = basename($module);
      if ( !file_exists(DIR_FS_CATALOG . "includes/apps/paypal/modules/$module/$module.php") ) {
        return false;
      }

      $key = 'OSCOM_APP_PAYPAL_' . $module . '_STATUS';
      return defined($key) && !Text::is_empty(constant($key));
    }

    function getModuleInfo($module, $info) {
      $class = 'OSCOM_PayPal_' . $module;

      if ( !class_exists($class) ) {
        $this->loadLanguageFile("modules/$module/$module.php");

        include DIR_FS_CATALOG . "includes/apps/paypal/modules/$module/$module.php";
      }

      $m = new $class();

      return $m->{'_' . $info};
    }

    function hasCredentials($module, $type = null) {
      if ( !defined('OSCOM_APP_PAYPAL_' . $module . '_STATUS') ) {
        return false;
      }

      $server = constant('OSCOM_APP_PAYPAL_' . $module . '_STATUS');

      if ( !in_array($server, ['1', '0']) ) {
        return false;
      }

      $server = ($server == '1') ? 'LIVE' : 'SANDBOX';

      if ( $type == 'email') {
        $creds = ['OSCOM_APP_PAYPAL_' . $server . '_SELLER_EMAIL'];
      } elseif ( substr($type, 0, 7) == 'payflow' ) {
        if ( strlen($type) > 7 ) {
          $creds = ['OSCOM_APP_PAYPAL_PF_' . $server . '_' . strtoupper(substr($type, 8))];
        } else {
          $creds = [
            'OSCOM_APP_PAYPAL_PF_' . $server . '_VENDOR',
            'OSCOM_APP_PAYPAL_PF_' . $server . '_PASSWORD',
            'OSCOM_APP_PAYPAL_PF_' . $server . '_PARTNER',
          ];
        }
      } else {
        $creds = [
          'OSCOM_APP_PAYPAL_' . $server . '_API_USERNAME',
          'OSCOM_APP_PAYPAL_' . $server . '_API_PASSWORD',
          'OSCOM_APP_PAYPAL_' . $server . '_API_SIGNATURE',
        ];
      }

      foreach ( $creds as $c ) {
        if ( !defined($c) || (strlen(trim(constant($c))) < 1) ) {
          return false;
        }
      }

      return true;
    }

    function getCredentials($module, $type) {
      if ( constant('OSCOM_APP_PAYPAL_' . $module . '_STATUS') == '1' ) {
        if ( $type == 'email') {
          return constant('OSCOM_APP_PAYPAL_LIVE_SELLER_EMAIL');
        } elseif ( $type == 'email_primary') {
          return constant('OSCOM_APP_PAYPAL_LIVE_SELLER_EMAIL_PRIMARY');
        } elseif ( substr($type, 0, 7) == 'payflow' ) {
          return constant('OSCOM_APP_PAYPAL_PF_LIVE_' . strtoupper(substr($type, 8)));
        } else {
          return constant('OSCOM_APP_PAYPAL_LIVE_API_' . strtoupper($type));
        }
      }

      if ( $type == 'email') {
        return constant('OSCOM_APP_PAYPAL_SANDBOX_SELLER_EMAIL');
      } elseif ( $type == 'email_primary') {
        return constant('OSCOM_APP_PAYPAL_SANDBOX_SELLER_EMAIL_PRIMARY');
      } elseif ( substr($type, 0, 7) == 'payflow' ) {
        return constant('OSCOM_APP_PAYPAL_PF_SANDBOX_' . strtoupper(substr($type, 8)));
      } else {
        return constant('OSCOM_APP_PAYPAL_SANDBOX_API_' . strtoupper($type));
      }
    }

    function hasApiCredentials($server, $type = null) {
      $server = ($server === 'live') ? 'LIVE' : 'SANDBOX';

      if ( $type == 'email') {
        $creds = ['OSCOM_APP_PAYPAL_' . $server . '_SELLER_EMAIL'];
      } elseif ( substr($type, 0, 7) == 'payflow' ) {
        $creds = ['OSCOM_APP_PAYPAL_PF_' . $server . '_' . strtoupper(substr($type, 8))];
      } else {
        $creds = [
          'OSCOM_APP_PAYPAL_' . $server . '_API_USERNAME',
          'OSCOM_APP_PAYPAL_' . $server . '_API_PASSWORD',
          'OSCOM_APP_PAYPAL_' . $server . '_API_SIGNATURE',
        ];
      }

      foreach ( $creds as $c ) {
        if ( !defined($c) || (strlen(trim(constant($c))) < 1) ) {
          return false;
        }
      }

      return true;
    }

    function getApiCredentials($server, $type) {
      $key = (('live' === $server)
            ? 'OSCOM_APP_PAYPAL_LIVE_API_'
            : 'OSCOM_APP_PAYPAL_SANDBOX_API_')
           . strtoupper($type);

      if ( defined($key) ) {
        return constant($key);
      }
    }

    function getParameters($module) {
      $result = [];

      if ( 'G' === $module ) {
        $path = DIR_FS_CATALOG . 'includes/apps/paypal/cfg_params/';
        $base = 'OSCOM_APP_PAYPAL_';
      } else {
        $path = DIR_FS_CATALOG . "includes/apps/paypal/modules/$module/cfg_params/";
        $base = 'OSCOM_APP_PAYPAL_' . $module . '_';
      }

      if ( $dir = @dir($path) ) {
        while ( $file = $dir->read() ) {
          if ( !is_dir("$path$file") && (pathinfo($file, PATHINFO_EXTENSION) === 'php') ) {
            $result[] = $base . strtoupper(pathinfo($file, PATHINFO_FILENAME));
          }
        }
      }

      return $result;
    }

    function getInputParameters($module) {
      $result = [];

      if ( $module == 'G' ) {
        $cut = 'OSCOM_APP_PAYPAL_';
      } else {
        $cut = 'OSCOM_APP_PAYPAL_' . $module . '_';
      }

      $cut_length = strlen($cut);

      foreach ( $this->getParameters($module) as $key ) {
        $p = strtolower(substr($key, $cut_length));

        if ( $module == 'G' ) {
          $cfg_class = 'OSCOM_PayPal_Cfg_' . $p;

          if ( !class_exists($cfg_class) ) {
            $this->loadLanguageFile('cfg_params/' . $p . '.php');

            include(DIR_FS_CATALOG . 'includes/apps/paypal/cfg_params/' . $p . '.php');
          }
        } else {
          $cfg_class = 'OSCOM_PayPal_' . $module . '_Cfg_' . $p;

          if ( !class_exists($cfg_class) ) {
            $this->loadLanguageFile('modules/' . $module . '/cfg_params/' . $p . '.php');

            include(DIR_FS_CATALOG . 'includes/apps/paypal/modules/' . $module . '/cfg_params/' . $p . '.php');
          }
        }

        $cfg = new $cfg_class();

        if ( !defined($key) ) {
          $this->saveParameter($key, $cfg->default, ($cfg->title ?? null), ($cfg->description ?? null), ($cfg->set_func ?? null));
        }

        if ( !isset($cfg->app_configured) || ($cfg->app_configured !== false) ) {
          if ( isset($cfg->sort_order) && is_numeric($cfg->sort_order) ) {
            $counter = (int)$cfg->sort_order;
          } else {
            $counter = count($result);
          }

          while ( true ) {
            if ( isset($result[$counter]) ) {
              $counter++;

              continue;
            }

            $set_field = $cfg->getSetField();

            if ( !empty($set_field) ) {
              $result[$counter] = $set_field;
            }

            break;
          }
        }
      }

      ksort($result, SORT_NUMERIC);

      return $result;
    }

// APP calls require $server to be "live" or "sandbox"
    function getApiResult($module, $call, $extra_params = null, $server = null, $is_ipn = false) {
      if ( $module == 'APP' ) {
        $function = 'OSCOM_PayPal_Api_' . $call;

        if ( !function_exists($function) ) {
          require DIR_FS_CATALOG . 'includes/apps/paypal/api/' . $call . '.php';
        }
      } else {
        if ( !isset($server) ) {
          $server = (constant('OSCOM_APP_PAYPAL_' . $module . '_STATUS') == '1') ? 'live' : 'sandbox';
        }

        $function = 'OSCOM_PayPal_' . $module . '_Api_' . $call;

        if ( !function_exists($function) ) {
          include(DIR_FS_CATALOG . 'includes/apps/paypal/modules/' . $module . '/api/' . $call . '.php');
        }
      }

      $result = $function($this, $server, $extra_params);

      $this->log($module, $call, ($result['success'] === true) ? 1 : -1, $result['req'], $result['res'], $server, $is_ipn);

      return $result['res'];
    }

    function makeApiCall($url, $parameters = null, $headers = null, $opts = null) {
      $server = parse_url($url);

      if ( !isset($server['port']) ) {
        $server['port'] = ($server['scheme'] == 'https') ? 443 : 80;
      }

      if ( !isset($server['path']) ) {
        $server['path'] = '/';
      }

      $curl = curl_init($server['scheme'] . '://' . $server['host'] . $server['path'] . (isset($server['query']) ? '?' . $server['query'] : ''));
      curl_setopt($curl, CURLOPT_PORT, $server['port']);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
      curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
      curl_setopt($curl, CURLOPT_ENCODING, ''); // disable gzip

      if ( isset($parameters) ) {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
      }

      if ( isset($headers) && is_array($headers) && !empty($headers) ) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      }

      if ( isset($server['user']) && isset($server['pass']) ) {
        curl_setopt($curl, CURLOPT_USERPWD, $server['user'] . ':' . $server['pass']);
      }

      if ( defined('OSCOM_APP_PAYPAL_VERIFY_SSL') && (OSCOM_APP_PAYPAL_VERIFY_SSL == '1') ) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        if ( (substr($server['host'], -10) == 'paypal.com') && file_exists(DIR_FS_CATALOG . 'ext/modules/payment/paypal/paypal.com.crt') ) {
          curl_setopt($curl, CURLOPT_CAINFO, DIR_FS_CATALOG . 'ext/modules/payment/paypal/paypal.com.crt');
        } elseif ( file_exists(DIR_FS_CATALOG . 'includes/cacert.pem') ) {
          curl_setopt($curl, CURLOPT_CAINFO, DIR_FS_CATALOG . 'includes/cacert.pem');
        }
      } else {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      }

      if (substr($server['host'], -10) == 'paypal.com') {
        $ssl_version = 0;

        if ( defined('OSCOM_APP_PAYPAL_SSL_VERSION') && (OSCOM_APP_PAYPAL_SSL_VERSION == '1') ) {
          $ssl_version = 6;
        }

        if (isset($opts['sslVersion']) && is_int($opts['sslVersion'])) {
          $ssl_version = $opts['sslVersion'];
        }

        if ($ssl_version !== 0) {
          curl_setopt($curl, CURLOPT_SSLVERSION, $ssl_version);
        }
      }

      if ( defined('OSCOM_APP_PAYPAL_PROXY') && !Text::is_empty(OSCOM_APP_PAYPAL_PROXY) ) {
        curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($curl, CURLOPT_PROXY, OSCOM_APP_PAYPAL_PROXY);
      }

      $result = curl_exec($curl);

      if (isset($opts['returnFull']) && ($opts['returnFull'] === true)) {
        $result = [
          'response' => $result,
          'error' => curl_error($curl),
          'info' => curl_getinfo($curl),
        ];
      }

      curl_close($curl);

      return $result;
    }

    function drawButton($title = null, $link = null, $type = null, $params = null, $force_css = false) {
      $colours = [
        'success' => 'success',
        'error' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
        'primary' => 'primary',
      ];

      if ( !isset($type) || !in_array($type, array_keys($colours)) ) {
        $type = 'info';
      }

      $button = '';

      if ( isset($link) ) {
        $button .= '<a href="' . $link . '" class="btn btn-' . $colours[$type] . ' pp-button';

        if ( isset($type) ) {
          $button .= ' pp-button-' . $type;
        }

        $button .= '"';

        if ( isset($params) ) {
          $button .= ' ' . $params;
        }

        //if ( $force_css == true ) {
          //$button .= ' style="' . $css . '"';
        //}

        $button .= '>' . $title . '</a>';
      } else {
        $button .= '<button type="submit" class="btn btn-' . $colours[$type] . ' pp-button';

        if ( isset($type) ) {
          $button .= ' pp-button-' . $type;
        }

        $button .= '"';

        if ( isset($params) ) {
          $button .= ' ' . $params;
        }

        //if ( $force_css == true ) {
        //  $button .= ' style="' . $css . '"';
        //}

        $button .= '>' . $title . '</button>';
      }

      return $button;
    }

    function createRandomValue($length, $type = 'mixed') {
      return Password::create_random($length, $type);
    }

    function saveParameter($key, $value, $title = null, $description = null, $set_func = null) {
      global $db;

      if ( defined($key) ) {
        $db->query("UPDATE configuration SET configuration_value = '" . $db->escape($value) . "' WHERE configuration_key = '" . $db->escape($key) . "'");
      } else {
        $sql = 'INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, ';
        if ( isset($set_func) ) {
          $sql .= 'set_function, ';
        }
        $sql .= "date_added) VALUES ('" . $db->escape($title ?? 'PayPal App Parameter')
              . "', '" . $db->escape($key) . "', '" . $db->escape($value) . "', '"
              . $db->escape($description ?? 'A parameter for the PayPal Application.')
              . "', 6, 0, ";
        if ( isset($set_func) ) {
          $sql .= "'" . $db->escape($set_func) . "', ";
        }
        $sql .= 'NOW())';

        $db->query($sql);

        define($key, $value);
      }
    }

    function deleteParameter($key) {
      $GLOBALS['db']->query("DELETE FROM configuration WHERE configuration_key = '" . $GLOBALS['db']->escape($key) . "'");
    }

    function formatCurrencyRaw($total, $currency_code = null, $currency_value = null) {
      return $GLOBALS['currencies']->format_raw($total, true, $currency_code, $currency_value);
    }

    function getCode() {
      return $this->_code;
    }

    function getTitle() {
      return $this->_title;
    }

    function getVersion() {
      if ( !isset($this->_version) ) {
        $version = trim(file_get_contents(DIR_FS_CATALOG . 'includes/apps/paypal/version.txt'));

        if ( is_numeric($version) ) {
          $this->_version = $version;
        } else {
          trigger_error('APP [PAYPAL]: Could not read App version number.');
        }
      }

      return $this->_version;
    }

    function getApiVersion() {
      return $this->_api_version;
    }

    function getIdentifier() {
      return $this->_identifier;
    }

    function hasAlert() {
      return isset($_SESSION['OSCOM_PayPal_Alerts']);
    }

    function addAlert($message, $type) {
      if ( in_array($type, ['error', 'warning', 'success']) ) {
        if ( !isset($_SESSION['OSCOM_PayPal_Alerts'][$type]) ) {
          if ( !isset($_SESSION['OSCOM_PayPal_Alerts']) ) {
            $_SESSION['OSCOM_PayPal_Alerts'] = [];
          }

          $_SESSION['OSCOM_PayPal_Alerts'][$type] = [];
        }

        $_SESSION['OSCOM_PayPal_Alerts'][$type][] = $message;
      }
    }

    function getAlerts() {
      $output = '';

      if ( !empty($_SESSION['OSCOM_PayPal_Alerts']) ) {
        $results = [];

        foreach ( $_SESSION['OSCOM_PayPal_Alerts'] as $type => $messages ) {
          if ( in_array($type, ['error', 'warning', 'success']) ) {
            $m = '';

            foreach ( $messages as $message ) {
              $m .= '<div class="alert alert-' . $type . '">';
                $m .= htmlspecialchars($message);
              $m .= '</div>';
            }

            $results[] = $m;
          }
        }

        if ( $results ) {
          $output .= '<div class="pp-alerts">' . implode("\n", $results) . '</div>';
        }
      }

      unset($_SESSION['OSCOM_PayPal_Alerts']);

      return $output;
    }

    function install($module) {
      $cut_length = strlen('OSCOM_APP_PAYPAL_' . $module . '_');

      foreach ( $this->getParameters($module) as $key ) {
        $p = strtolower(substr($key, $cut_length));

        $cfg_class = 'OSCOM_PayPal_' . $module . '_Cfg_' . $p;

        if ( !class_exists($cfg_class) ) {
          $this->loadLanguageFile("modules/$module/cfg_params/$p.php");

          include DIR_FS_CATALOG . "includes/apps/paypal/modules/$module/cfg_params/$p.php";
        }

        $cfg = new $cfg_class();

        $this->saveParameter($key, $cfg->default, ($cfg->title ?? null), ($cfg->description ?? null), ($cfg->set_func ?? null));
      }

      $m_class = "OSCOM_PayPal_$module";

      if ( !class_exists($m_class) ) {
        $this->loadLanguageFile("modules/$module/$module.php");

        include DIR_FS_CATALOG . "includes/apps/paypal/modules/$module/$module.php";
      }

      $m = new $m_class();

      if ( method_exists($m, 'install') ) {
        $m->install($this);
      }
    }

    function uninstall($module) {
      $GLOBALS['db']->query("DELETE FROM configuration WHERE configuration_key LIKE 'OSCOM_APP_PAYPAL_" . $GLOBALS['db']->escape($module) . "_%'");

      $m_class = "OSCOM_PayPal_$module";

      if ( !class_exists($m_class) ) {
        $this->loadLanguageFile("modules/$module/$module.php");

        include DIR_FS_CATALOG . "includes/apps/paypal/modules/$module/$module.php";
      }

      $m = new $m_class();

      if ( method_exists($m, 'uninstall') ) {
        $m->uninstall($this);
      }
    }

    function logUpdate($message, $version) {
      if ( is_writable(DIR_FS_CATALOG . 'includes/apps/paypal/work') ) {
        file_put_contents(DIR_FS_CATALOG . "includes/apps/paypal/work/update_log-$version.php", '[' . date('d-M-Y H:i:s') . '] ' . $message . "\n", FILE_APPEND);
      }
    }

    public function loadLanguageFile($filename, $lang = null) {
      $lang = basename($lang ?? $_SESSION['language']);

      if ( $lang !== 'english' ) {
        $this->loadLanguageFile($filename, 'english');
      }

      $pathname = DIR_FS_CATALOG . "includes/apps/paypal/languages/$lang/$filename";

      if ( !file_exists($pathname) ) {
        return;
      }

      foreach ( array_filter(array_map('trim', file($pathname) ?: []), function ($v) {
        return isset($v[0]) && ($v[0] !== '#');
      }) as $line )
      {
        $position = strpos($line, ' =');

        if ( is_int($position) && ctype_alpha($line[0]) && (strpos($key = trim(substr($line, 0, $position)), ' ') === false) ) {
          $this->_definitions[$key] = trim(substr($line, $position + strlen(' =')));
        } elseif ( isset($key) ) {
          $this->_definitions[$key] .= "\n" . $line;
        }
      }
    }

    function getDef($key, $values = null) {
      $def = $this->_definitions[$key] ?? $key;

      if ( is_array($values) ) {
        $keys = array_keys($values);

        foreach ( $keys as &$k ) {
          $k = ':' . $k;
        }

        $def = str_replace($keys, array_values($values), $def);
      }

      return $def;
    }

    function getDirectoryContents($base, &$result = []) {
      foreach ( scandir($base) as $file ) {
        if ( ($file == '.') || ($file == '..') ) {
          continue;
        }

        $pathname = $base . '/' . $file;

        if ( is_dir($pathname) ) {
          $this->getDirectoryContents($pathname, $result);
        } else {
          $result[] = str_replace('\\', '/', $pathname); // Unix style directory separator "/"
        }
      }

      return $result;
    }

    function isWritable($location) {
      while ( !file_exists($location) ) {
        $location = dirname($location);
      }

      return is_writable($location);
    }

    function rmdir($dir) {
      foreach ( array_diff(scandir($dir), ['.', '..']) as $file ) {
        $path = "$dir/$file";
        if ( is_dir($path) ) {
          $this->rmdir($path);
        } else {
          unlink($path);
        }
      }

      return rmdir($dir);
    }

    function displayPath($pathname) {
      return ( DIRECTORY_SEPARATOR === '/' )
           ? $pathname
           : str_replace('/', DIRECTORY_SEPARATOR, $pathname);
    }

  }
