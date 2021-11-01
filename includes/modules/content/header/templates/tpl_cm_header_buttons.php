<div class="col-sm-<?= (int)MODULE_CONTENT_HEADER_BUTTONS_CONTENT_WIDTH ?> text-right cm-header-buttons">
  <div class="btn-group" role="group" aria-label="...">
    <?php
    echo new Button(MODULE_CONTENT_HEADER_BUTTONS_TITLE_CART_CONTENTS . ($_SESSION['cart']->count_contents() > 0 ? ' (' . $_SESSION['cart']->count_contents() . ')' : ''), 'fas fa-shopping-cart', '', [], $GLOBALS['Linker']->build('shopping_cart.php'))
       . new Button(MODULE_CONTENT_HEADER_BUTTONS_TITLE_CHECKOUT, 'fas fa-credit-card', '', [], $GLOBALS['Linker']->build('checkout_shipping.php'))
       . new Button(MODULE_CONTENT_HEADER_BUTTONS_TITLE_MY_ACCOUNT, 'fas fa-user', '', [], $GLOBALS['Linker']->build('account.php'));

    if ( isset($_SESSION['customer_id']) ) {
      echo new Button(MODULE_CONTENT_HEADER_BUTTONS_TITLE_LOGOFF, 'fas fa-sign-out-alt', '', [], $GLOBALS['Linker']->build('logoff.php'));
    }
    ?>
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
