<div class="<?= MODULE_CONTENT_FOOTER_EXTRA_ICONS_CONTENT_WIDTH ?> cm-footer-extra-icons">
  <p><?php

  if ( is_string($brand_icons)) {
    echo $brand_icons;
  } else {
    foreach ($brand_icons as $icon ) {
      echo '<i class="' . $icon . ' mr-1 me-1"></i> ';
    }
  }

?></p>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>