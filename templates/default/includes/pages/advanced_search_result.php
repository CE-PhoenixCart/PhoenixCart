<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, Guarantor::ensure_global('Linker')->build('advanced_search.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('advanced_search_result.php')->retain_query_except());

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= HEADING_TITLE_2 ?></h1>

<?php
  require 'includes/system/segments/sortable_product_listing.php';
?>

  <div class="mt-3">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('advanced_search.php')->retain_query_except(['sort', 'page'])) ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
