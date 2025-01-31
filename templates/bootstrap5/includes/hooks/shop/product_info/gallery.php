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
    if (isset($GLOBALS['product']) && ($GLOBALS['product'] instanceof Product)) {
      if ($GLOBALS['product']->get('status') == 1) {
        if (defined('MODULE_CONTENT_PI_GALLERY_STATUS') && ('True' === MODULE_CONTENT_PI_GALLERY_STATUS)) {
          $swipe_arrows = MODULE_CONTENT_PI_GALLERY_SWIPE_ARROWS;
          $gallery_indicators = MODULE_CONTENT_PI_GALLERY_INDICATORS;
          
          $modal_size = MODULE_CONTENT_PI_GALLERY_MODAL_SIZE;
          $album_name = sprintf(MODULE_CONTENT_PI_GALLERY_ALBUM_NAME, $GLOBALS['product']->get('name'));
          $album_exit = MODULE_CONTENT_PI_GALLERY_ALBUM_CLOSE;
        }
        elseif (defined('PI_GALLERY_STATUS') && ('True' === PI_GALLERY_STATUS)) {
          $swipe_arrows = PI_GALLERY_SWIPE_ARROWS;
          $gallery_indicators = PI_GALLERY_INDICATORS;
          
          $modal_size = PI_GALLERY_MODAL_SIZE;
          $album_name = sprintf(PI_GALLERY_ALBUM_NAME, $GLOBALS['product']->get('name'));
          $album_exit = PI_GALLERY_ALBUM_CLOSE;
        }
        else {
          $swipe_arrows = GALLERY_SWIPE_ARROWS;
          $gallery_indicators = GALLERY_INDICATORS;
          
          $modal_size = GALLERY_MODAL_SIZE;
          $album_name = sprintf(GALLERY_ALBUM_NAME, $GLOBALS['product']->get('name'));
          $album_exit = GALLERY_ALBUM_CLOSE;
        }
        
        $label_next = GALLERY_NEXT_ITEM;
        $label_prev = GALLERY_PREV_ITEM;
        
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
      
        $display_swipe_arrows = $display_indicators = '';
      
        if ($swipe_arrows === 'True') {
          $display_swipe_arrows = '<a class="carousel-control-prev" href="#carousel" role="button" data-bs-slide="prev" aria-label="' . $label_prev . '"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-prev-icon mt-1"></span></span></a><a class="carousel-control-next" href="#carousel" role="button" data-bs-slide="next" aria-label="' . $label_next . '"><span class="border border-white bg-secondary rounded" aria-hidden="true"><span class="carousel-control-next-icon mt-1"></span></span></a>';
        } 

        if ($gallery_indicators === 'True') {
          $display_indicators = '<div class="carousel-indicators">';
            $display_indicators .= '<button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active border border-white bg-secondary rounded" aria-label="' . sprintf(GALLERY_TO_ITEM, '0') . '"></button>';
            for ($i = 1, $n = count($other_images); $i <= $n; $i++) {
              $display_indicators .= '<button type="button" data-bs-target="#carousel" data-bs-slide-to="' . $i . '" class="border border-white bg-secondary rounded" aria-label="' . sprintf(GALLERY_TO_ITEM, $i) . '"></button>';
            }
          $display_indicators .= '</div>';
        }
        
        $bs_theme = BOOTSTRAP_THEME;

        $modal_gallery_footer = <<<mgf
<div id="lightbox" class="modal fade" role="dialog">
  <div class="modal-dialog {$modal_size}" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="carousel slide" data-bs-theme="{$bs_theme}" data-bs-ride="carousel" tabindex="-1" id="carousel">
          {$display_indicators}
          <div class="carousel-inner">
            <div class="carousel-item text-center active">{$first_img}</div>
            {$other_img}
          </div>
          {$display_swipe_arrows}
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

}