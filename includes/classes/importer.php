<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

class importer {

  public $_data = [];

  public function __construct($iID) {
    $this->buildImporter($iID);
  }

  public function buildImporter($iID) {
    $importer_query = $GLOBALS['db']->query("select i.*, ii.* from importers i, importers_info ii where i.importers_id = " . (int)$iID . " and i.importers_id = ii.importers_id and ii.languages_id = " . (int)$_SESSION['languages_id']);

    if ( mysqli_num_rows($importer_query) === 1 ) {
      $this->_data = $importer_query->fetch_assoc();
    } else {
      error_log("No unique importer for [$iID:{$_SESSION['languages_id']}]");
    }
  }

  public function getData($key) {
    return $this->_data[$key];
  }

  public function showImage() {
    return new Image('images/' . $this->_data['importers_image'], [], $this->_data['importers_name']);
  }

  public function buildImporterArray() {
    return $this->_data;
  }

}
