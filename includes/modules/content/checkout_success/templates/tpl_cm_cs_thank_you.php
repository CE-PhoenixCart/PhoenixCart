<div class="col-sm-<?= $content_width ?> cm-cs-thank-you">
  <h5 class="mb-1"><?= MODULE_CONTENT_CHECKOUT_SUCCESS_TEXT_THANKS_FOR_SHOPPING ?></h5>

  <div class="border">
    <ul class="list-group list-group-flush">
      <li class="list-group-item bg-light"><?= MODULE_CONTENT_CHECKOUT_SUCCESS_TEXT_SUCCESS ?></li>
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

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
