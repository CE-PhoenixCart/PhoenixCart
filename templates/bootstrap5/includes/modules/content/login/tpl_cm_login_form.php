<div class="<?= MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH ?> cm-login-form">

<?php
  echo new Form('login', $GLOBALS['Linker']->build('login.php', ['action' => 'process']), 'post', ['class' => 'was-validated']);
  $GLOBALS['customer_data']->act_on('username', 'display_input');
  $GLOBALS['customer_data']->act_on('password', 'display_input');
?>

  <div class="d-grid">
    <?= new Button(IMAGE_BUTTON_LOGIN, 'fas fa-sign-in-alt', 'btn-success') ?>
  </div>

  </form>

</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
