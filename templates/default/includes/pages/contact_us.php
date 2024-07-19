<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE, $Linker->build('contact_us.php'));

  require $Template->map('template_top.php', 'component');
  
  if ($messageStack->size('contact') > 0) {
    echo $messageStack->output('contact');
  }
  ?>

  <div class="row">
    <?= $Template->get_content('contact_us') ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
