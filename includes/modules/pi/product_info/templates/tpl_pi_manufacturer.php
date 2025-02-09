<div class="<?= PI_MANUFACTURER_CONTENT_WIDTH ?> pi-manufacturer">
  <p class="fs-6 fw-semibold mb-1"><?= PI_MANUFACTURER_HEADING ?></p>
  
  <div class="row align-items-center">
    <?php
    if (!Text::is_empty($_image)) {
      echo '<div class="col-4 text-center">';
        echo new Image("images/$_image", [], htmlspecialchars($_brand));
      echo '</div>';
    }
    ?>
    <div class="col">
      <ul class="list-group">
        <?php
        foreach($filtered as $f) {
          echo '<li class="list-group-item">' . $f . '</li>';
        }
        ?>
      </ul>
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
