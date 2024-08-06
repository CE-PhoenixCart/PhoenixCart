<div class="<?= PI_BUY_CONTENT_WIDTH ?> pi-buy-button">
  <?=
    new Button(PI_BUY_BUTTON_TEXT, 'fas fa-shopping-cart', 'btn-success btn-block btn-lg btn-product-info btn-buy'),
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
