<div class="<?= MODULE_CONTENT_FOOTER_CONTACT_US_CONTENT_WIDTH ?> cm-footer-contact-us">
  <h4><?= MODULE_CONTENT_FOOTER_CONTACT_US_HEADING_TITLE ?></h4>
  <address>
    <strong><?= STORE_NAME ?></strong><br>
    <?= nl2br(STORE_ADDRESS) ?><br>
    <?= MODULE_CONTENT_FOOTER_CONTACT_US_PHONE . STORE_PHONE ?><br>
    <?= MODULE_CONTENT_FOOTER_CONTACT_US_EMAIL . STORE_OWNER_EMAIL_ADDRESS ?>
  </address>
  <?php
  if (!Text::is_empty(STORE_TAX_ID)) {
    echo sprintf(MODULE_CONTENT_FOOTER_CONTACT_US_TAX_ID, STORE_TAX_ID);
  }
  ?>
  <ul class="list-unstyled">
    <li><a class="btn btn-success btn-block" role="button" href="<?= $GLOBALS['Linker']->build('contact_us.php') ?>"><i class="fas fa-paper-plane"></i> <?= MODULE_CONTENT_FOOTER_CONTACT_US_EMAIL_LINK ?></a></li>
  </ul>
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
