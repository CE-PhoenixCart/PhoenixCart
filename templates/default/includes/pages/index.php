<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require $Template->map('template_top.php', 'component');

  if ($category_depth == 'nested') {

    if ($messageStack->size('product_action') > 0) {
      echo $messageStack->output('product_action');
    }
?>

  <div class="row">
    <?= $Template->get_content('index_nested') ?>
  </div>

<?php
  } elseif ($category_depth == 'products' || !empty($_GET['manufacturers_id'])) {

?>

  <div class="row">
    <?= $Template->get_content('index_products') ?>
  </div>

<?php
  } else { // default page

    if ($messageStack->size('product_action') > 0) {
      echo $messageStack->output('product_action');
    }
?>

<div class="row">
  <?= $Template->get_content('index') ?>
</div>

<?php
  }

  require $Template->map('template_bottom.php', 'component');
?>
