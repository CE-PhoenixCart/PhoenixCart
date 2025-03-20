<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_SET_PASSWORD_NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_SET_PASSWORD_NAVBAR_TITLE_2, $Linker->build());

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= MODULE_CONTENT_ACCOUNT_SET_PASSWORD_HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('account_password') > 0) {
    echo $messageStack->output('account_password');
  }

  echo (new Form('account_password', $Linker->build()))->hide('action', 'process');
?>

  <p class="text-danger text-end"><?= FORM_REQUIRED_INFORMATION ?></p>

<?php
  $customer_data->display_input($page_fields);
?>

  <p><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-success btn-lg btn-block') ?></p>
  <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?></p>


</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
