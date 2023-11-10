<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_admin_catalog_jQueryUI {

  public $version = '1.13.2';

  public function listen_injectSiteStart() {
    $jQueryUI = '<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
    
    return $jQueryUI;
  }

}
