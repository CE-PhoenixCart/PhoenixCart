<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_gdpr_store_data {

  public function listen_injectData() {
    global $port_my_data;

    $port_my_data['US']['NAME'] = STORE_NAME;
    $port_my_data['US']['URL'] = $GLOBALS['Linker']->build('index.php');
    $port_my_data['US']['OWNER'] = STORE_OWNER;
    $port_my_data['US']['EMAIL'] = STORE_OWNER_EMAIL_ADDRESS;
    $port_my_data['US']['ADDRESS'] = STORE_ADDRESS;
    $port_my_data['US']['TELEPHONE'] = STORE_PHONE;
  }

  public function listen_portData() {
    global $port_my_data;

    if (isset($_GET['action'])) {
      switch($_GET['action']) {
        case 'gdpr_data':
        $data_dump = json_encode($port_my_data, JSON_PRETTY_PRINT);

        $file = ['GDPR', STORE_NAME, $_SESSION['customer_id']];
        $filename = urlencode(implode('_', $file));

        header('Content-disposition: attachment; filename=' . $filename . '.json');
        header('Content-type: application/json');

        echo $data_dump;

        exit;
      }
    }
  }

  public function listen_injectRedirect() {
    global $customer;

    $geo_location = $customer->get('country_id');

    if (defined('MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES')) {
      if (strlen(MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES) > 0) {
        $eu_countries = explode(';', MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES);

        if (!in_array($geo_location, $eu_countries)) {
          Href::redirect($GLOBALS['Linker']->build('privacy.php'));
        }
      }
    }
  }

}