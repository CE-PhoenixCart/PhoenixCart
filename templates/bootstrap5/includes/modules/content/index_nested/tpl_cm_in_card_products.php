<div class="<?= MODULE_CONTENT_IN_CARD_PRODUCTS_CONTENT_WIDTH ?> cm-in-card-products">
  <p class="fs-4 fw-semibold mb-1"<?= MODULE_CONTENT_IN_CARD_PRODUCTS_HEADING ?></p>

  <div class="<?= IS_PRODUCT_PRODUCTS_DISPLAY_ROW ?>">
    <?php
    $card = [
      'show_buttons' => 'True' === PRODUCT_LIST_BUTTONS,
    ];
    
    while ($card_product = $card_products_query->fetch_assoc()) {
      $product = new Product($card_product);
      ?>
      <div class="col mb-2">
        <div class="card h-100 is-product" <?= $product->get('data_attributes') ?>>
          <?php include $GLOBALS['Template']->map('product_card.php', 'component'); ?>
        </div>
      </div>
      <?php
    }
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
