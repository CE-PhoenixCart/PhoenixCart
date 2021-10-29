<div class="col-sm-<?= (int)PI_BUY_CONTENT_WIDTH ?> pi-buy-button mt-2">
  <?=
    new Button(PI_BUY_BUTTON_TEXT, 'fas fa-shopping-cart', 'btn-success btn-block btn-lg btn-product-info btn-buy', [
      'data-has-attributes' => (int)$GLOBALS['product']->get('has_attributes'),
      'data-in-stock' => (int)$GLOBALS['product']->get('in_stock'),
      'data-product-id' => (int)$GLOBALS['product']->get('id'),
    ]),
    new Input('products_id', ['value' => (int)$GLOBALS['product']->get('id')], 'hidden');
  ?>
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
