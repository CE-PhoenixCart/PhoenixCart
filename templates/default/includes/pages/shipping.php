<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $page = info_pages::get_page([
    'p.slug' => 'shipping',
    'pd.languages_id' => (int)$_SESSION['languages_id'],
  ]);

  $breadcrumb->add($page['pages_title'], $Linker->build('shipping.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= $page['pages_title'] ?></h1>

  <?= $page['pages_text'] ?>

  <p><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-light btn-block btn-lg', [], $Linker->build('index.php')) ?></p>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
