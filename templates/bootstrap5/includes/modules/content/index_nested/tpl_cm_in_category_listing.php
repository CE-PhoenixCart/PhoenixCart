<div class="<?= MODULE_CONTENT_IN_CATEGORY_LISTING_CONTENT_WIDTH ?> cm-in-category-listing">
  <div class="<?= MODULE_CONTENT_IN_CATEGORY_LISTING_DISPLAY_ROW ?>">
    <?php
    foreach ($categories as $v) {
      $link = $GLOBALS['Linker']->build('index.php', ['cPath' => $v['id']]);
      echo '<div class="col">';
        echo '<div class="card is-category mb-2 text-center h-100">';
          echo '<div class="card-body">';
            echo '<a href="' . $link . '">' . new Image('images/' . $v['image'], [], htmlspecialchars($v['title'])) . '</a>';
          echo '</div>';
          echo '<div class="card-footer">';
            echo '<a class="text-dark-emphasis" href="' . $link . '">' . $v['title'] . '</a>';
          echo '</div>';
        echo '</div>' . PHP_EOL;
      echo '</div>';
    }
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
