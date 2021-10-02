<div class="card mb-2 bm-shopping-cart">
  <div class="card-header"><a href="<?= $GLOBALS['Linker']->build('shopping_cart.php') ?>"><?= MODULE_BOXES_SHOPPING_CART_BOX_TITLE ?></a></div>
  <div class="list-group list-group-flush box-cart-list">
    <?php
  if ($_SESSION['cart']->count_contents() > 0) {
    foreach ($_SESSION['cart']->get_products() as $product) {
      echo '<a class="list-group-item list-group-item-action';
      if (isset($_SESSION['new_products_id_in_cart']) && ($product->get('uprid') == $_SESSION['new_products_id_in_cart'])) {
        echo ' active';
        unset($_SESSION['new_products_id_in_cart']);
      }
      echo '" href="' . $GLOBALS['Linker']->build('product_info.php', ['products_id' => $product->get('uprid')]) . '">',
           sprintf(MODULE_BOXES_SHOPPING_CART_BOX_ITEM_QUANTITY, $product->get('quantity'), $product->get('name')),
           '</a>';
    }
  } else {
    echo '<span class="list-group-item">' . MODULE_BOXES_SHOPPING_CART_BOX_CART_EMPTY . '</span>';
  }
    ?>
  </div>
  <div class="card-footer text-right">
    <?= $cart_totalised ?>
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
