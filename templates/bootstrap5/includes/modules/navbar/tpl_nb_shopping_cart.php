<li class="nav-item dropdown nb-shopping-cart">
  <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasCart" role="button" aria-controls="offcanvasCart">
    <?php printf(NAVBAR_ICON_CART_CONTENTS, $_SESSION['cart']->count_contents(), ' d-none d-sm-inline'); ?>
  </a>
</li>

<?php
$push_to_footer = '<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-theme="' . BOOTSTRAP_THEME . '" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">';

  $push_to_footer .= '<div class="offcanvas-header bg-body-tertiary">';  
    $push_to_footer .= '<h5 class="offcanvas-title" id="offcanvasCartLabel">';
      $push_to_footer .= sprintf(MODULE_NAVBAR_SHOPPING_CART_HAS_CONTENTS, $_SESSION['cart']->count_contents(), $GLOBALS['currencies']->format($_SESSION['cart']->show_total()));
    $push_to_footer .= '</h5>';
    $push_to_footer .= '<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="' . IMAGE_BUTTON_CLOSE . '"></button>';
  $push_to_footer .= '</div>';
  $push_to_footer .= '<div class="offcanvas-body p-0 overflow-auto">'; 
    $push_to_footer .= '<div class="list-group list-group-flush list-group-cart">';
      if ($_SESSION['cart']->count_contents() > 0) {
        $goto = 'checkout_shipping.php';
        
        foreach ($_SESSION['cart']->get_products() as $product) {
          if ($product->lacks_stock()) $goto = 'shopping_cart.php';
          
          $push_to_footer .= '<a href="' . $product->get('link') . '" class="list-group-item list-group-item-action">';
            $push_to_footer .= '<div class="d-flex w-100 justify-content-between align-items-center">';
              $push_to_footer .= '<div class="w-75">';
                $push_to_footer .= '<h6 class="mb-1">' . sprintf(MODULE_NAVBAR_SHOPPING_CART_PRODUCT, $product->get('quantity'), $product->get('name')) . '</h6>';
                $attributes = $product->get('attributes');
                foreach (($product->get('attribute_selections') ?? []) as $option => $value) {
                  $push_to_footer .= '<small class="text-muted">- ' . $attributes[$option]['name'] . ' ' . $attributes[$option]['values'][$value]['name'] . '</small><br>';
                }
                $push_to_footer .= '</div>';
              $push_to_footer .= '<div class="w-25">';
                $push_to_footer .= new Image('images/' . $product->get('image'), [], htmlspecialchars($product->get('name')));
              $push_to_footer .= '</div>';
            $push_to_footer .= '</div>';
          $push_to_footer .= '</a>';
        }
      }
    $push_to_footer .= '</div>';

    if ($_SESSION['cart']->count_contents() > 0) {
      $push_to_footer .= '<div class="cart-buttons position-sticky bottom-0 pb-2" style="z-index:1;">';
        $push_to_footer .= '<hr class="mt-0">';
        $push_to_footer .= '<div class="d-flex justify-content-between m-2">';
          $push_to_footer .= '<a href="' . $GLOBALS['Linker']->build('shopping_cart.php') . '" role="button" class="btn btn-info">' . MODULE_NAVBAR_SHOPPING_CART_VIEW_CART . '</a>';
          $push_to_footer .= '<a href="' . $GLOBALS['Linker']->build($goto) . '" role="button" class="btn btn-success">' . MODULE_NAVBAR_SHOPPING_CART_CHECKOUT . '</a>';
        $push_to_footer .= '</div>';
      $push_to_footer .= '</div>';
    }
    else {
      $push_to_footer .= '<div class="mt-5 text-center empty-cart">';
        $push_to_footer .= '<p class="display-4">' . MODULE_NAVBAR_SHOPPING_CART_NO_CONTENTS . '<p>';
      $push_to_footer .= '</div>';
    }
  $push_to_footer .= '</div>';
  
$push_to_footer .= '</div>';

$GLOBALS['Template']->add_block($push_to_footer . PHP_EOL, 'footer_scripts');

/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
