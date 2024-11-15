<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_shop_product_info_gallery {

  public function listen_injectBodyEnd() {
    if ( (defined('PI_GALLERY_STATUS') && ('True' === PI_GALLERY_STATUS)) || (defined('PI_GALLERY_IMAGES_STATUS') && ('True' === PI_GALLERY_IMAGES_STATUS)) ) {
      $first_img = new Image('images/' . $GLOBALS['product']->get('image'), ['alt' => htmlspecialchars($GLOBALS['product']->get('name')), 'loading' => 'lazy']);
      
      $other_images = $GLOBALS['db']->fetch_all("SELECT image, htmlcontent FROM products_images WHERE products_id = '" . (int)$GLOBALS['product']->get('id') . "' ORDER BY sort_order");
      
      $other_img_indicator = $other_img = '';

      if (count($other_images) > 0) {
        foreach ($other_images as $k => $v) {
          $other_img .= '<div class="carousel-item text-center">';
          $other_img .= new Image('images/' . $v['image'], ['alt' => htmlspecialchars($GLOBALS['product']->get('name')), 'loading' => 'lazy']);
          if (!Text::is_empty($v['htmlcontent'])) {
            $other_img .= '<div class="carousel-caption d-none d-md-block">';
              $other_img .= $v['htmlcontent'];
            $other_img .= '</div>';
          }
          $other_img .= '</div>';
        }
      }
      
      $swipe_arrows = $indicators = '';
      
      if (PI_GALLERY_SWIPE_ARROWS === 'True') {
        $swipe_arrows = '<a class="carousel-control-prev" href="#carousel" role="button" data-bs-slide="prev"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-prev-icon mt-1"></span></span></a><a class="carousel-control-next" href="#carousel" role="button" data-bs-slide="next"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-next-icon mt-1"></span></span></a>';
      } 

      if (PI_GALLERY_INDICATORS === 'True') {
        $indicators = '<div class="carousel-indicators">';
        $indicators .= '<button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active border border-white bg-secondary rounded"></button>';
        for ($i = 1, $n = count($other_images); $i <= $n; $i++) {
          $indicators .= '<button type="button" data-bs-target="#carousel" data-bs-slide-to="' . $i . '" class="border border-white bg-secondary rounded"></button>';
        }
      $indicators .= '</div>';
      }
      
      $modal_size = PI_GALLERY_MODAL_SIZE;
      $album_name = sprintf(PI_GALLERY_ALBUM_NAME, $GLOBALS['product']->get('name'));
      $album_exit = PI_GALLERY_ALBUM_CLOSE;
      
      $bs_theme = BOOTSTRAP_THEME;

      $modal_gallery_footer = <<<mgf
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
  mgf;

      return $modal_gallery_footer;
    }
  }

}