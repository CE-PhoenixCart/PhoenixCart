<div class="<?= MODULE_CONTENT_FOOTER_EXTRA_ICONS_CONTENT_WIDTH ?> cm-footer-extra-icons">
  <p><?php

  if ( is_string($brand_icons)) {
    echo $brand_icons;
  } else {
    foreach ($brand_icons as $icon ) {
      echo '<i class="fab fa-' . $icon . ' fa-lg"></i> ';
    }
  }

?></p>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>