<?php
  if (isset($testimonials_query)) {
?>

<div class="<?= MODULE_CONTENT_TESTIMONIALS_LIST_CONTENT_WIDTH ?> cm-t-list">
  
  <div class="row">
    <?php
    while ($testimonials = $testimonials_query->fetch_assoc()) {
      echo '<div class="' . MODULE_CONTENT_TESTIMONIALS_LIST_CONTENT_WIDTH_EACH . '">' . PHP_EOL;
        echo '<figure class="border-3 border-start px-3">';
          echo '<blockquote class="blockquote lead">' . PHP_EOL;
            echo nl2br($testimonials['testimonials_text']) . PHP_EOL;
          echo '</blockquote>' . PHP_EOL;
          echo '<figcaption class="blockquote-footer pt-2">',
                  sprintf(MODULE_CONTENT_TESTIMONIALS_LIST_WRITERS_NAME_DATE, htmlspecialchars($testimonials['customers_name']), Date::abridge($testimonials['date_added'])),
               '</figcaption>' . PHP_EOL;
        echo '</figure>';
      echo '</div>' . PHP_EOL;
    }
    ?>
  </div>
  
  <div class="row align-items-center">
    <div class="col d-none d-sm-block">
      <?= $testimonials_split->display_count(MODULE_CONTENT_TESTIMONIALS_DISPLAY_NUMBER) ?>
    </div>
    <div class="col">
      <?= $testimonials_split->display_links(MAX_DISPLAY_PAGE_LINKS) ?>
    </div>
  </div>
  
</div>

<?php
  } else {
?>

<div class="col">
  <div class="alert alert-info" role="alert">
    <?= MODULE_CONTENT_TESTIMONIALS_LIST_NO_TESTIMONIALS ?>
  </div>
</div>

<?php
  }

/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
