<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE, $Linker->build('gdpr.php'));

  $page_content = $Template->get_content('gdpr');

  $hooks->call('gdpr', 'portData');

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

  <div class="row">
    <?= $page_content ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
