<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, Guarantor::ensure_global('Linker')->build('advanced_search.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('advanced_search_result.php')->retain_parameters());

  require $oscTemplate->map_to_template('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE_2 ?></h1>

<?php
  require 'includes/system/segments/sortable_product_listing.php';
?>

  <br>

  <div class="buttonSet">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', null, [], $Linker->build('advanced_search.php')->retain_parameters(['sort', 'page'])) ?>
  </div>

<?php
  require $oscTemplate->map_to_template('template_bottom.php', 'component');
?>
