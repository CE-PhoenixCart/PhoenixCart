<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $page = info_pages::get_page([
    'p.slug' => 'privacy',
    'pd.languages_id' => (int)$_SESSION['languages_id'],
  ]);

  $breadcrumb->add($page['pages_title'], $Linker->build('privacy.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= $page['pages_title'] ?></h1>

  <?= $page['pages_text'] ?>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-light btn-block btn-lg', [], $Linker->build('index.php')) ?></div>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
