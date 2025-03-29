<div class="<?= MODULE_CONTENT_PI_REVIEW_STARS_CONTENT_WIDTH ?> cm-pi-review-stars">
  <ul class="list-inline">
    <?php
  foreach ($review_ratings as $i => $rating) {
    echo '<li class="list-inline-item ' . $i . '">' . $rating . '</li>';
  }
?>
    <li class="list-inline-item border-start ms-2 ps-3"><a href="<?= $review_link ?>"><?= $do_review ?></a></li>
  </ul>
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
