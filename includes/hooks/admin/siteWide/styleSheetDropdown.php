<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_admin_siteWide_styleSheetDropdown {

  function listen_injectSiteStart() {
    $admin_css = '<style>#navbarAdmin ul.navbar-nav li.dropdown:hover > ul.dropdown-menu { max-height:500px; overflow-y:auto; }</style>' . PHP_EOL;

    return $admin_css;
  }

}
