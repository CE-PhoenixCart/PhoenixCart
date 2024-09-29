<div class="<?= MODULE_CONTENT_PI_BUY_CONTENT_WIDTH ?> cm-pi-buy-button">
  <div class="d-grid">
    <?=
    new Button(MODULE_CONTENT_PI_BUY_BUTTON_TEXT, 'fas fa-shopping-cart', 'btn-success btn-lg btn-product-info btn-buy'),
    new Input('products_id', ['value' => (int)$product->get('id')], 'hidden')
    ?>
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