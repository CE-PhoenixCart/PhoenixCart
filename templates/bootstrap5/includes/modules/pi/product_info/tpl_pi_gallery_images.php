<div class="<?= PI_GALLERY_IMAGES_CONTENT_WIDTH ?> pi-gallery-images">
  <div class="row">
    <?php
    $pi_thumbnails = '';
    foreach ($other_images as $k => $v) {
      $pi_thumbnails .= '<div class="' . PI_GALLERY_IMAGES_CONTENT_WIDTH_EACH . '">';
        $pi_thumbnails .= '<a role="button" data-bs-target="#lightbox" data-bs-toggle="modal" data-bs-slide="' . ($k+1) . '">';
          $pi_thumbnails .= new Image('images/' . $v['image'], ['alt' => htmlspecialchars($GLOBALS['product']->get('name')), 'loading' => 'lazy']);
        $pi_thumbnails .= '</a>';
      $pi_thumbnails .= '</div>';
    }
    
    echo $pi_thumbnails;
    ?>
  </div>
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
