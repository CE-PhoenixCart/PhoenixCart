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

  <div class="row">
    <?= $Template->get_content('advanced_search_result') ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
