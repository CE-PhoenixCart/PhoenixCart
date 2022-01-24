<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_admin_siteWide_chartJs {

  public $version = '2.9.3';

  public $sitestart = null;

  public function listen_injectSiteStart() {
    if (basename(Request::get_page() === 'index.php')) {
      return '<!-- chartJs Hooked -->' . PHP_EOL
           . '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>' . PHP_EOL;
    }
  }

}
