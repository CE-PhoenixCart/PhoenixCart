<div class="<?= MODULE_CONTENT_PI_GALLERY_CONTENT_WIDTH ?> cm-pi-gallery">
  <a role="button" data-bs-target="#lightbox" data-bs-toggle="modal" data-bs-slide="0"><?=
    new Image('images/' . $active_image['image'], ['alt' => htmlspecialchars( $active_image['htmlcontent'])])
  ?></a>
  <?php
  $first_img = new Image('images/' . $active_image['image'], ['alt' => htmlspecialchars($active_image['htmlcontent']), 'loading' => 'lazy']);

// now create the thumbs
  if (count($other_images) > 0) {
    $pi_thumb = '<div class="row">';
    foreach ($other_images as $k => $v) {
      $pi_thumb .= '<div class="' . MODULE_CONTENT_PI_GALLERY_CONTENT_WIDTH_EACH . '">';
        $pi_thumb .= '<a role="button" data-bs-target="#lightbox" data-bs-toggle="modal" data-bs-slide="' . ($k+1) . '">';
          $pi_thumb .= new Image('images/' . $v['image'], ['loading' => 'lazy']);
        $pi_thumb .= '</a>';
      $pi_thumb .= '</div>';
    }
    $pi_thumb .= '</div>';

    $other_img = '';
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

    $swipe_arrows = '';
    if (MODULE_CONTENT_PI_GALLERY_SWIPE_ARROWS == 'True') {
      $swipe_arrows = <<<'EOHTML'
<a class="carousel-control-prev" href="#carousel" role="button" data-bs-slide="prev">
  <span class="border border-white bg-secondary rounded" aria-hidden="true">
    <span class="carousel-control-prev-icon mt-1"></span>
  </span>
</a>
<a class="carousel-control-next" href="#carousel" role="button" data-bs-slide="next">
  <span class="border border-white bg-secondary rounded" aria-hidden="true">
    <span class="carousel-control-next-icon mt-1"></span>
  </span>
</a>

EOHTML;
    }

    if (MODULE_CONTENT_PI_GALLERY_INDICATORS === 'True') {
      $indicators = '<div class="carousel-indicators">';
        $indicators .= '<button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active border border-white bg-secondary rounded"></button>';
        for ($i = 1, $n = count($other_images); $i <= $n; $i++) {
          $indicators .= '<button type="button" data-bs-target="#carousel" data-bs-slide-to="' . $i . '" class="border border-white bg-secondary rounded"></button>';
        }
      $indicators .= '</div>';
    } else {
      $indicators = '';
    }

    $modal_size = MODULE_CONTENT_PI_GALLERY_MODAL_SIZE;
    $album_name = sprintf(MODULE_CONTENT_PI_GALLERY_ALBUM_NAME, $GLOBALS['product']->get('name'));
    $album_exit = MODULE_CONTENT_PI_GALLERY_ALBUM_CLOSE;
    
    $bs_theme = BOOTSTRAP_THEME;

    $modal_gallery_footer = <<<"EOHTML"
<div id="lightbox" class="modal fade" role="dialog">
  <div class="modal-dialog {$modal_size}" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="carousel slide" data-bs-theme="{$bs_theme}" data-bs-ride="carousel" tabindex="-1" id="carousel">
          {$indicators}
          <div class="carousel-inner">
            <div class="carousel-item text-center active">{$first_img}</div>
            {$other_img}
          </div>
          {$swipe_arrows}
        </div>
      </div>
      <div class="modal-footer">
        <h5 class="text-uppercase me-auto">{$album_name}</h5>
        <a href="#" role="button" data-bs-dismiss="modal" class="btn btn-primary px-3">{$album_exit}</a>
      </div>
    </div>
  </div>
</div>
EOHTML;

    $GLOBALS['Template']->add_block($modal_gallery_footer, 'footer_scripts');
  }
?>

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
