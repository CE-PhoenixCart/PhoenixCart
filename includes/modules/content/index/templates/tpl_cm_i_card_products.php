<div class="col-sm-<?= MODULE_CONTENT_CARD_PRODUCTS_CONTENT_WIDTH ?> cm-i-card-products">
  <h4><?php printf(MODULE_CONTENT_CARD_PRODUCTS_HEADING, strftime('%B')); ?></h4>

  <div class="<?= IS_PRODUCT_PRODUCTS_DISPLAY_ROW ?>">
    <?php
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

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
