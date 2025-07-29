<div class="<?= PI_QTY_INPUT_CONTENT_WIDTH ?> pi-qty-input">

  <div class="input-group">
    <?php if (PI_QTY_INPUT_BUTTONS == 'True') : ?>
      <button type="button" class="btn btn-secondary spinner" data-spin="minus" aria-label="<?= PI_QTY_INPUT_DECREASE ?>" aria-controls="pi-qty-spin"><i class="fa fa-minus" aria-hidden="true"></i></button>
      <?= (new Input('qty', ['min' => '1', 'class' => 'form-control form-control-lg', 'id' => 'pi-qty-spin', 'inputmode' => 'numeric', 'aria-labelledby' => 'spinner-label', 'aria-live' => 'polite', ], 'number'))->default_value('1'); ?>
      <button type="button" class="btn btn-secondary spinner" data-spin="plus" aria-label="<?= PI_QTY_INPUT_INCREASE ?>" aria-controls="pi-qty-spin"><i class="fa fa-plus" aria-hidden="true"></i></button>
    <?php else : ?>
      <span class="input-group-text"><?= PI_QTY_INPUT_BUTTON_TEXT ?></span>
      <?= (new Input('qty', ['min' => '1', 'class' => 'form-control', 'id' => 'pi-qty-spin', 'inputmode' => 'numeric', 'aria-labelledby' => 'spinner-label', 'aria-live' => 'polite',], 'number'))->default_value('1'); ?>
    <?php endif; ?>
  </div>

  <span id="spinner-label" class="visually-hidden"><?= PI_QTY_INPUT_BUTTON_TEXT ?></span>
  
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
