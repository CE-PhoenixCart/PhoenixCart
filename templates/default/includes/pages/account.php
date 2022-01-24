<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE, $Linker->build('account.php'));

  require $Template->map('template_top.php', 'component');

  if ($messageStack->size('account') > 0) {
    echo $messageStack->output('account');
  }
?>

<div class="row"><?= $Template->get_content('account') ?></div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
