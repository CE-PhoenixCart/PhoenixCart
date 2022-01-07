<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE);

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

  <div class="alert alert-danger" role="alert">
    <?= TEXT_MAIN ?>
  </div>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-danger btn-lg btn-block', [], $Linker->build('index.php')) ?></div>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
