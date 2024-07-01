<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_siteWide_Favicon {

  public function listen_injectSiteStart() {
    $favicon = ''; $array = ['16', '128', '192', '256'];
    
    foreach ($array as $size) {
      $favicon_image = HTTP_SERVER . DIR_WS_CATALOG . 'images/favicon/' . $size . '_' . FAVICON_LOGO;
      
      $favicon .= <<<favicon
<link rel="icon" href="{$favicon_image}" sizes="{$size}x{$size}" />

favicon;
    }
    
    $favicon .= '<link rel="apple-touch-icon" href="' . HTTP_SERVER . DIR_WS_CATALOG . 'images/favicon/192_' . FAVICON_LOGO . '" />' . PHP_EOL; 
    
    echo $favicon;
  }
}
