<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_siteWide_breadCrumb {

  function listen_injectBodyContentStart() {
    
    if ( defined('MODULE_CONTENT_HEADER_BREADCRUMB_STATUS') && ('True' === MODULE_CONTENT_HEADER_BREADCRUMB_STATUS) ) {
      $crumbs = null; $active = max(array_keys($GLOBALS['breadcrumb']->trail())); 
      
      foreach ($GLOBALS['breadcrumb']->trail() as $k => $v) {
        if (isset($v['link']) && !Text::is_empty($v['link'])) {
          if ($k == $active) {
            $crumbs .= '<li class="breadcrumb-item active" aria-current="page">' . $v['title'] . '</li>' . PHP_EOL;
          }
          else {
            $crumbs .= '<li class="breadcrumb-item"><a href="' . $v['link'] . '">' . $v['title'] . '</a></li>' . PHP_EOL;
          }
        } else {
          $crumbs .= ($k == $active) ? '<li class="breadcrumb-item active" aria-current="page">' : '<li class="breadcrumb-item">';
            $crumbs .= $v['title'];
          $crumbs .= '</li>' . PHP_EOL;
        }
      }
    
    
      $depth = $GLOBALS['category_depth'] ?? '';
    
      $trail = <<<bc
<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-transparent px-0 pt-2 my-1">
    {$crumbs}
  </ol>
</nav>
bc;

      if ( ($depth !== 'top') || (isset($_GET['manufacturers_id'])) ) {
        return $trail;
      }
    }
  }

}
