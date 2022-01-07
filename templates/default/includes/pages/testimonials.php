<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE, $Linker->build('testimonials.php'));

  $page_content = $Template->get_content('testimonials');

  require $Template->map('template_top.php', 'component');
?>

  <div class="row">
    <?= $page_content ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
