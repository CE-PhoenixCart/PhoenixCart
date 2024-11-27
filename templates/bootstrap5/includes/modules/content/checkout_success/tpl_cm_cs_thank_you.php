<div class="<?= MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_CONTENT_WIDTH ?> cm-cs-thank-you">
  <p class="fs-5 fw-semibold mb-1"><?= MODULE_CONTENT_CHECKOUT_SUCCESS_TEXT_THANKS_FOR_SHOPPING ?></p>

  <div class="border">
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><?= MODULE_CONTENT_CHECKOUT_SUCCESS_TEXT_SUCCESS ?></li>
    </ul>

    <div class="list-group list-group-flush">
      <?=
      sprintf(MODULE_CONTENT_CHECKOUT_SUCCESS_TEXT_SEE_ORDERS, $GLOBALS['Linker']->build('account_history.php')),
      sprintf(MODULE_CONTENT_CHECKOUT_SUCCESS_TEXT_CONTACT_STORE_OWNER, $GLOBALS['Linker']->build('contact_us.php'))
      ?>
    </div>
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
