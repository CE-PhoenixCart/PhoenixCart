<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_siteWide_modalFix {
  
  function listen_injectBodyEnd() {
    $modalfix = <<<EOD
<script>
document.addEventListener('show.bs.modal', e => e.target.inert = false);
document.addEventListener('hide.bs.modal', e => e.target.inert = true);
</script>
EOD;

    return $modalfix;
  }
}
