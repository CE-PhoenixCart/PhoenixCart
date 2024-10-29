<div class="<?= I_BRAND_ICONS_CONTENT_WIDTH ?> i-brand-icons">
  <h4><?= I_BRAND_ICONS_HEADING ?></h4>
  
  <div class="d-none d-sm-block">
    <div class="d-flex flex-wrap">
      <?= $i_brand_output ?>
    </div>
  </div>
  <div class="d-block d-sm-none">
    <div id="iCarousel" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <?= $i_brand_xs_output ?>
      </div>
      <a class="carousel-control-prev" href="#iCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#iCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>
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
