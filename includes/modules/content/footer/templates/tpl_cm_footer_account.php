<div class="col-sm-6 col-md-<?= (int)MODULE_CONTENT_FOOTER_ACCOUNT_CONTENT_WIDTH ?> cm-footer-account">
  <h4><?= MODULE_CONTENT_FOOTER_ACCOUNT_HEADING_TITLE ?></h4>
  <nav class="nav nav-pills flex-column">

<?php
  if ( isset($_SESSION['customer_id']) ) {
?>
    <a class="nav-link pl-0" href="<?= $GLOBALS['Linker']->build('account.php') ?>"><?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_ACCOUNT ?></a>
    <a class="nav-link pl-0" href="<?= $GLOBALS['Linker']->build('address_book.php') ?>"><?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_ADDRESS_BOOK ?></a>
    <a class="nav-link pl-0" href="<?= $GLOBALS['Linker']->build('account_history.php') ?>"><?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_ORDER_HISTORY ?></a>
    <a class="nav-link mt-2 btn btn-danger btn-block" role="button" href="<?= $GLOBALS['Linker']->build('logoff.php') ?>"><i class="fas fa-sign-out-alt"></i> <?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_LOGOFF ?></a>

<?php
    } else {
?>
    <a class="nav-link pl-0" href="<?= $GLOBALS['Linker']->build('create_account.php') ?>"><?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_CREATE_ACCOUNT ?></a>
    <a class="nav-link mt-2 btn btn-success btn-block" role="button" href="<?= $GLOBALS['Linker']->build('login.php') ?>"><i class="fas fa-sign-in-alt"></i> <?= MODULE_CONTENT_FOOTER_ACCOUNT_BOX_LOGIN ?></a>

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

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
