<div class="<?= MODULE_CONTENT_PI_GALLERY_CONTENT_WIDTH ?> cm-pi-gallery">
  <a role="button" data-target="#lightbox" data-toggle="modal" data-slide="0"><?=
    new Image('images/' . $GLOBALS['product']->get('image'), ['class' => 'img-fluid mx-auto d-block', 'alt' => htmlspecialchars($GLOBALS['product']->get('name'))])
  ?></a>
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
