<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('login.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('password_forgotten.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('password_forgotten') > 0) {
    echo $messageStack->output('password_forgotten');
  }

  if ($password_reset_initiated == true) {
?>

  <div class="alert alert-success" role="alert"><?= TEXT_PASSWORD_RESET_INITIATED ?></div>

<?php
  } else {
?>

<?= new Form('password_forgotten', $Linker->build('password_forgotten.php', ['action' => 'process']), 'post', [], true) ?>

  <div class="alert alert-warning" role="alert"><?= TEXT_MAIN ?></div>

  <?php
  $customer_data->display_input(['email_address']);
  ?>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_RESET_PASSWORD, 'fas fa-user-cog', 'btn-warning btn-lg btn-block') ?></div>
    <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', '', [], $Linker->build('login.php')) ?></p>
  </div>

</form>

<?php
  }

  require $Template->map('template_bottom.php', 'component');
?>
