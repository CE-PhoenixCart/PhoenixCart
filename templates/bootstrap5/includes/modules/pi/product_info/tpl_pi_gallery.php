<div class="<?= PI_GALLERY_CONTENT_WIDTH ?> pi-gallery">
  <a role="button" data-bs-target="#lightbox" data-bs-toggle="modal" data-bs-slide="0">
    <?= new Image('images/' . $GLOBALS['product']->get('image'), ['class' => 'img-fluid mx-auto d-block', 'alt' => htmlspecialchars($GLOBALS['product']->get('name'))]) ?>
  </a>
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
