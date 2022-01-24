<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  $page_content = $Template->get_content('create_account_success');

  require $Template->map('template_top.php', 'component');
?>

  <div class="row">
    <?= $page_content ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
