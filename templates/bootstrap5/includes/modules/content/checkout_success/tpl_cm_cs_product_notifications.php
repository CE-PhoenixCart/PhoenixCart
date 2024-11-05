<div class="<?= MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_CONTENT_WIDTH ?> cm-cs-product-notifications">
  <p class="fs-5 fw-semibold mb-1"><?= MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_TEXT_NOTIFY_PRODUCTS ?></p>

  <div class="border">
    <ul class="list-group list-group-flush">
      <?php
      foreach ($products_displayed as $id => $name) {
        echo '<li class="list-group-item">';
        echo '<div class="form-check">';
        echo new Tickable('notify[]', [
          'value' => $id,
          'class' => 'form-check-input',
          'id' => 'notify_' . $id,
        ], 'checkbox');
        echo '<label class="form-check-label" for="notify_' . $id . '">' . $name . '</label>';
        echo '</div>';
        echo '</li>' . PHP_EOL;
      }
      ?>
    </ul>
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
