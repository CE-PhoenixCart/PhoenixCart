<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $containers = info_pages::getContainer([
    'pd.pages_id' => (int)$_GET['pages_id'],
    'pd.languages_id' => (int)$_SESSION['languages_id'],
    'p.pages_status' => 1,
  ]);

  if (empty($containers)) {
    Href::redirect($Linker->build('index.php'));
  }

  $page = $containers[0];
  $breadcrumb->add($page['pages_title'], $Linker->build('info.php', ['pages_id' => (int)$page['pages_id']]));

  require $Template->map('template_top.php', 'component');

  $page_content = $Template->get_content('info');
  ?>

    <div class="row">
      <?= $page_content ?>
    </div>

  <?php
  require $Template->map('template_bottom.php', 'component');
?>
