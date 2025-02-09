<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build());

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size($message_stack_area) > 0) {
    echo $messageStack->output($message_stack_area);
  }

  echo (new Form('account_edit', $Linker->build(), 'post', ['class' => 'was-validated']))->hide('action', 'process');

  $customer_data->display_input($customer_data->get_fields_for_page('account_edit'), $customer->fetch_to_address());
  echo $hooks->cat('injectFormDisplay');
?>

  <div class="d-grid">
    <?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-success btn-lg') ?>
  </div>
  
  <div class="my-2">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?>
  </div>
  
</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
