<div class="col-sm-<?= (int)MODULE_CONTENT_PRODUCT_INFO_ALSO_PURCHASED_CONTENT_WIDTH ?> cm-pi-also-purchased">
  <h4><?= MODULE_CONTENT_PRODUCT_INFO_ALSO_PURCHASED_PUBLIC_TITLE ?></h4>

  <div class="<?= IS_PRODUCT_PRODUCTS_DISPLAY_ROW ?>">
    <?php
    $card = [
      'show_buttons' => true,
    ];

    while ($also_purchased = $also_purchased_query->fetch_assoc()) {
      $product = new Product($also_purchased);
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
