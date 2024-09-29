<div class="<?= MODULE_CONTENT_CREATE_ACCOUNT_LINK_CONTENT_WIDTH ?> cm-create-account-link">
  <p class="alert alert-info" role="alert"><?= MODULE_CONTENT_LOGIN_TEXT_NEW_CUSTOMER ?></p>
  <p><?= MODULE_CONTENT_LOGIN_TEXT_NEW_CUSTOMER_INTRODUCTION ?></p>

  <div class="d-grid">
    <?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-primary', [], $GLOBALS['Linker']->build('create_account.php')) ?>
  </div>
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