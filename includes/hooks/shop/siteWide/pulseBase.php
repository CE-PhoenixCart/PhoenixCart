<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_siteWide_pulseBase {

  public function listen_injectBodyEnd() {
    $pulse = <<<HTML
<script src="./ext/modules/pulse/pulse.js" defer></script>
<script>
  window.pulse = window.pulse || [];
</script>
HTML;

   return PHP_EOL . $pulse;
  }

}
