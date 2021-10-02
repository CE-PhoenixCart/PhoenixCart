<li class="nav-item dropdown nb-shopping-cart">
  <a class="nav-link dropdown-toggle" href="#" id="navDropdownCart" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php printf(MODULE_NAVBAR_SHOPPING_CART_CONTENTS, $_SESSION['cart']->count_contents()); ?>
  </a>

  <div class="dropdown-menu<?= (('Right' === MODULE_NAVBAR_SHOPPING_CART_CONTENT_PLACEMENT) ? ' dropdown-menu-right' : '') ?>" aria-labelledby="navDropdownCart">
    <?php
    echo '<a class="dropdown-item" href="', $GLOBALS['Linker']->build('shopping_cart.php'), '">',
         sprintf(
           MODULE_NAVBAR_SHOPPING_CART_HAS_CONTENTS,
           $_SESSION['cart']->count_contents(),
           $GLOBALS['currencies']->format($_SESSION['cart']->show_total())
         ),
         '</a>';
    if ($_SESSION['cart']->count_contents() > 0) {
      echo '<div class="dropdown-divider"></div>', PHP_EOL;
      echo '<div class="dropdown-cart-list">';
      foreach ($_SESSION['cart']->get_products() as $product) {
        echo '<a class="dropdown-item" href="', $product->get('link'), '">',
          sprintf(MODULE_NAVBAR_SHOPPING_CART_PRODUCT, $product->get('quantity'), $product->get('name')),
          '</a>';
      }
      echo '</div>', PHP_EOL;
      echo '<div class="dropdown-divider"></div>', PHP_EOL;
      echo '<a class="dropdown-item" href="', $GLOBALS['Linker']->build('checkout_shipping.php'), '">', MODULE_NAVBAR_SHOPPING_CART_CHECKOUT, '</a>', PHP_EOL;
    }
    ?>
  </div>
</li>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
