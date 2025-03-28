<div class="<?= I_BRAND_ICONS_CONTENT_WIDTH ?> i-brand-icons">
  <p class="fs-5 fw-semibold card-title my-3"><?= I_BRAND_ICONS_HEADING ?></p>
  
  <div class="d-none d-sm-block">
    <div class="d-flex flex-wrap">
      <?php
      foreach ($i_brand_array as $i => $o) {
        echo '<a class="list-group-item border-0 p-2" href="' . $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => (int)$o['manufacturers_id']]) . '">' . new Image('images/' . $o['manufacturers_image'], [], htmlspecialchars($o['manufacturers_name'])) . '</a>';
      }
      ?>
    </div>
  </div>
  <div class="d-block d-sm-none">
    <div id="iCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        $n = 1;
        foreach ($i_brand_xs_array as $i => $o) {
          $active = ($n == 1) ? ' active' : null;
          echo '<div class="carousel-item' . $active . '">';
            echo '<div class="list-group list-group-horizontal justify-content-center">';
              foreach ($o as $item) {
                echo '<a class="list-group-item border-0" href="' . $GLOBALS['Linker']->build('index.php', ['manufacturers_id' => (int)$item['manufacturers_id']]) . '">' . new Image('images/' . $item['manufacturers_image'], [], htmlspecialchars($item['manufacturers_name'])) . '</a>';
              }
            echo '</div>';
          echo '</div>';
        }
        ?>
      </div>
      <a class="carousel-control-prev" href="#iCarousel" role="button" data-bs-slide="prev">
        <span class="border border-white bg-secondary rounded" aria-hidden="true">
        <span class="carousel-control-prev-icon mt-1" aria-hidden="true"></span>
        </span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#iCarousel" role="button" data-bs-slide="next">
        <span class="border border-white bg-secondary rounded" aria-hidden="true">
        <span class="carousel-control-next-icon mt-1" aria-hidden="true"></span>
        </span>
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
