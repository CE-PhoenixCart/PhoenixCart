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
window.addEventListener('hide.bs.modal', () => {
  if (document.activeElement instanceof HTMLElement) {
    document.activeElement.blur();
  }
});
</script>
EOD;

    return $modalfix;
  }
}
