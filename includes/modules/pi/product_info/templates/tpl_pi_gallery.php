<div class="col-sm-<?= (int)PI_GALLERY_CONTENT_WIDTH ?> pi-gallery">
  <a href="#lightbox" class="lb" data-toggle="modal" data-slide="0"><?=
    new Image('images/' . $active_image['image'], ['alt' => htmlspecialchars( $active_image['htmlcontent'])])
  ?></a>
  <?php
  $first_img = new Image('images/' . $active_image['image'], ['alt' => htmlspecialchars($active_image['htmlcontent']), 'loading' => 'lazy']);

// now create the thumbs
  if (count($other_images) > 0) {
    $pi_thumb = '<div class="row">';
    foreach ($other_images as $k => $v) {
      $pi_thumb .= '<div class="' . PI_GALLERY_CONTENT_WIDTH_EACH . '">';
      $pi_thumb .= '<a href="#lightbox" class="lb" data-toggle="modal" data-slide="' . ($k+1) . '">';
      $pi_thumb .= new Image('images/' . $v['image'], ['loading' => 'lazy']);
      $pi_thumb .= '</a>';
      $pi_thumb .= '</div>';
    }
    $pi_thumb .= '</div>';

    $other_img_indicator = $other_img = '';
    foreach ($other_images as $k => $v) {
      $other_img .= '<div class="carousel-item text-center">';
      $other_img .= new Image('images/' . $v['image'], ['loading' => 'lazy']);
      if (!Text::is_empty($v['htmlcontent'])) {
        $other_img .= '<div class="carousel-caption d-none d-md-block">';
        $other_img .= $v['htmlcontent'];
        $other_img .= '</div>';
      }
      $other_img .= '</div>';
    }

    echo $pi_thumb;
  } else {
    $other_img = '';
  }

  if (PI_GALLERY_SWIPE_ARROWS === 'True') {
    $swipe_arrows = '<a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></a><a class="carousel-control-next" href="#carousel" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></a>';
  } else {
    $swipe_arrows = '';
  }

  if (PI_GALLERY_INDICATORS === 'True') {
    $indicators = '<ol class="carousel-indicators">';
      $indicators .= '<li data-target="#carousel" data-slide-to="0" class="pointer active"></li>';
      for ($i = 1, $n = count($other_images); $i <= $n; $i++) {
        $indicators .= '<li data-target="#carousel" data-slide-to="' . $i . '" class="pointer"></li>';
      }
    $indicators .= '</ol>';
  } else {
    $indicators = '';
  }

  $modal_size = PI_GALLERY_MODAL_SIZE;
  $album_name = sprintf(PI_GALLERY_ALBUM_NAME, $GLOBALS['product']->get('name'));
  $album_exit = PI_GALLERY_ALBUM_CLOSE;

  $modal_gallery_footer = <<<mgf
<div id="lightbox" class="modal fade" role="dialog">
  <div class="modal-dialog {$modal_size}" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="carousel slide" data-ride="carousel" tabindex="-1" id="carousel">
          {$indicators}
          <div class="carousel-inner">
            <div class="carousel-item text-center active">{$first_img}</div>
            {$other_img}
          </div>
          {$swipe_arrows}
        </div>
      </div>
      <div class="modal-footer">
        <h5 class="text-uppercase mr-auto">{$album_name}</h5>
        <a href="#" role="button" data-dismiss="modal" class="btn btn-primary px-3">{$album_exit}</a>
      </div>
    </div>
  </div>
</div>
mgf;

  $GLOBALS['Template']->add_block($modal_gallery_footer, 'footer_scripts');

  $modal_clicker = <<<mc
<script>$(document).ready(function() { $('a.lb').click(function(e) { var s = $(this).data('slide'); $('#lightbox').carousel(s); }); });</script>
mc;
  $GLOBALS['Template']->add_block($modal_clicker, 'footer_scripts');
  ?>
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
