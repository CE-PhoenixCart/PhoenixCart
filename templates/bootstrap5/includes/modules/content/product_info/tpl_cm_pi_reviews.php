<div class="<?= MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_WIDTH ?> cm-pi-reviews">
  <h4><?= MODULE_CONTENT_PRODUCT_INFO_REVIEWS_TEXT_TITLE ?></h4>
  <div class="row">
    <?php
    while ($review = $review_query->fetch_assoc()) {
      echo '<div class="col-sm-' . (int)MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_WIDTH_EACH . '">';
        echo '<figure>';
          echo '<blockquote class="blockquote">';
            echo htmlspecialchars($review['reviews_text']);
          echo '</blockquote>';
          echo '<figcaption class="blockquote-footer">';
            echo sprintf(MODULE_CONTENT_PRODUCT_INFO_REVIEWS_TEXT_RATED, new star_rating((float)$review['reviews_rating']), htmlspecialchars($review['customers_name']));
          echo'</figcaption>';
        echo '</figure>';
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

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
