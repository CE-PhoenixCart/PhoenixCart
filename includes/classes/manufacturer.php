<?php
/**
 * osCommerce Online Merchant
 *
 * @copyright Copyright (c) 2018 osCommerce; http://www.oscommerce.com
 * @license GNU General Public License; http://www.oscommerce.com/gpllicense.txt
 */

class manufacturer {

  public $_data = [];

  public function __construct($mID) {
    $this->buildManufacturer($mID);
  }

  public function buildManufacturer($mID) {
    $manufacturer_query = $GLOBALS['db']->query("select m.*, mi.* from manufacturers m, manufacturers_info mi where m.manufacturers_id = " . (int)$mID . " and m.manufacturers_id = mi.manufacturers_id and mi.languages_id = " . (int)$_SESSION['languages_id']);

    if ( mysqli_num_rows($manufacturer_query) === 1 ) {
      $this->_data = $manufacturer_query->fetch_assoc();
    } else {
      error_log("No unique manufacturer for [$mID:{$_SESSION['languages_id']}]");
    }
  }

  public function getData($key) {
    return $this->_data[$key];
  }

  public function showImage() {
    return new Image('images/' . $this->_data['manufacturers_image'], [], $this->_data['manufacturers_name']);
  }

  public function buildManufacturerArray() {
    return $this->_data;
  }

}
