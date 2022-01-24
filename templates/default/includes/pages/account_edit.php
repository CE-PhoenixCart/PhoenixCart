<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build());

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size($message_stack_area) > 0) {
    echo $messageStack->output($message_stack_area);
  }

  echo (new Form('account_edit', $Linker->build()))->hide('action', 'process');
?>

  <div class="text-danger text-right"><?= FORM_REQUIRED_INFORMATION ?></div>

  <?php
  $customer_data->display_input($customer_data->get_fields_for_page('account_edit'), $customer->fetch_to_address());
  echo $hooks->cat('injectFormDisplay');
?>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-success btn-lg btn-block') ?></div>
    <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', '', [], $Linker->build('account.php')) ?></p>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
