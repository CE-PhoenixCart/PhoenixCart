<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Wrapper for Paypal API calls
  Paypal Standard Payments
  Basic Paypal Payment Module for Phoenix Cart
  More sophisticated Paypal integration available at https://phoenixcart.org/forum/addons/

  author: John Ferguson @BrockleyJohn phoenix@cartmart.uk

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

class paypal_api {

  public $verifyssl, $proxy, $api;

  public function __construct($verifyssl, $proxy) {
    $this->verifyssl = $verifyssl;
    $this->proxy = $proxy;
  }

  public function makeCall($url, $parameters = null, $headers = null, $opts = null) {

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

    if ( $this->verifyssl == 'True' ) {
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

    if ( !Text::is_empty($this->proxy) ) {
      curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, true);
      curl_setopt($curl, CURLOPT_PROXY, $this->proxy);
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
}
