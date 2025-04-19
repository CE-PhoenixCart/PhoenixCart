<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $page = info_pages::get_page([
    'p.slug' => 'ssl_check',
    'pd.languages_id' => (int)$_SESSION['languages_id'],
  ]);

  $breadcrumb->add($page['pages_title'], $Linker->build('ssl_check.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= $page['pages_title'] ?></h1>

  <?= $page['pages_text'] ?>

  <div class="d-grid">
    <?= new Button(IMAGE_BUTTON_CONTACT_US, 'fas fa-paper-plane', 'btn-light btn-lg', [], $Linker->build('contact_us.php')) ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
