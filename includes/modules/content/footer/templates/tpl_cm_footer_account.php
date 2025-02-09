<div class="<?= MODULE_CONTENT_FOOTER_ACCOUNT_CONTENT_WIDTH ?> cm-footer-account">
  <p class="fs-4 fw-semibold mb-1"><?= MODULE_CONTENT_FOOTER_ACCOUNT_HEADING_TITLE ?></p>
  
  <nav class="nav nav-pills flex-column">
<?php
  if ( isset($_SESSION['customer_id']) ) {
?>
    <a class="nav-link ps-0 text-body-emphasis" href="<?= $GLOBALS['Linker']->build('account.php') ?>"><?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_ACCOUNT ?></a>
    <a class="nav-link ps-0 text-body-emphasis" href="<?= $GLOBALS['Linker']->build('address_book.php') ?>"><?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_ADDRESS_BOOK ?></a>
    <a class="nav-link ps-0 text-body-emphasis" href="<?= $GLOBALS['Linker']->build('account_history.php') ?>"><?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_ORDER_HISTORY ?></a>
    
    <div class="d-grid">
      <a class="mt-2 btn btn-danger" role="button" href="<?= $GLOBALS['Linker']->build('logoff.php') ?>"><i class="fas fa-sign-out-alt"></i> <?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_LOGOFF ?></a>
    </div>
    
<?php
    } else {
?>
    <a class="nav-link ps-0 text-body-emphasis" href="<?= $GLOBALS['Linker']->build('create_account.php') ?>"><?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_CREATE_ACCOUNT ?></a>
    
    <div class="d-grid">
      <a class="mt-2 btn btn-success" role="button" href="<?= $GLOBALS['Linker']->build('login.php') ?>"><i class="fas fa-sign-in-alt"></i> <?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_LOGIN ?></a>
    </div>

<?php
    }
?>
  </nav>
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
