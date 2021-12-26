<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE, $Linker->build('ssl_check.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

  <div class="card mb-2">
    <div class="card-header"><?= BOX_INFORMATION_HEADING ?></div>
    <div class="card-body">
      <?= BOX_INFORMATION ?>
    </div>
  </div>

  <div class="card mb-2 text-white bg-danger">
    <div class="card-body">
      <?= sprintf(TEXT_INFORMATION, $Linker->build('contact_us.php')) ?>
    </div>
  </div>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-light btn-block btn-lg', [], $Linker->build('login.php')) ?></div>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
