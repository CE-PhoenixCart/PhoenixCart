<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_siteWide_styleSheets {

  public $sitestart = null;

  function listen_injectSiteStart() {
    $this->sitestart .= '<style>* {min-height: 0.01px;} input:-webkit-autofill, select:-webkit-autofill { animation-name: onAutoFillStart; transition: background-color 50000s ease-in-out 0s; } input:not(:-webkit-autofill) { animation-name: onAutoFillCancel; }</style>';

    $css_file = 'templates/' . TEMPLATE_SELECTION . '/static/user.css';
    if (file_exists($css_file)) {
      $this->sitestart .= '<link href="' . $css_file . '" rel="stylesheet">' . PHP_EOL;
    }

    return $this->sitestart;
  }

}
