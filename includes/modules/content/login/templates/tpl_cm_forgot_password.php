<div class="col-sm-<?= (int)MODULE_CONTENT_FORGOT_PASSWORD_CONTENT_WIDTH ?> cm-forgot-password">

  <div class="alert alert-warning" role="alert"><?= MODULE_CONTENT_FORGOT_PASSWORD_INTRO_TEXT ?></div>

  <p><?= new Button(MODULE_CONTENT_FORGOT_PASSWORD_BUTTON_TEXT, 'fas fa-unlock-alt', 'btn-warning btn-block', [], $GLOBALS['Linker']->build('password_forgotten.php')) ?></p>

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
