<div class="col-sm-<?= (int)MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH ?> cm-login-form ">

  <p class="alert alert-success" role="alert"><?= MODULE_CONTENT_LOGIN_TEXT_RETURNING_CUSTOMER ?></p>

<?php
  echo new Form('login', $GLOBALS['Linker']->build('login.php', ['action' => 'process']), 'post');
  $GLOBALS['customer_data']->act_on('username', 'display_input');
  $GLOBALS['customer_data']->act_on('password', 'display_input');
?>

  <p class="text-right"><?= new Button(IMAGE_BUTTON_LOGIN, 'fas fa-sign-in-alt', 'btn-success btn-block') ?></p>

  </form>

</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
