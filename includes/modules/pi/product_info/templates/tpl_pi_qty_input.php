<div class="<?= PI_QTY_INPUT_CONTENT_WIDTH ?> pi-qty-input">

  <div class="input-group">
    <?php
    if (PI_QTY_INPUT_BUTTONS == 'True') {
      ?>
      <div class="input-group-prepend">
        <button class="btn btn-secondary spinner" type="button" data-spin="minus">
          <i class="fa fa-minus"></i>
        </button>
      </div>
      <?= (new Input('qty', ['min' => '1', 'class' => 'form-control form-control-lg', 'id' => 'pi-qty-spin'], 'number'))->default_value('1'); ?>
      <div class="input-group-append">
        <button class="btn btn-secondary spinner" type="button" data-spin="plus">
          <i class="fa fa-plus"></i>
        </button>
      </div>
      <?php
    }
    else {
      ?>
      <div class="input-group-prepend">
        <span class="input-group-text"><?= PI_QTY_INPUT_BUTTON_TEXT ?></span>
      </div>
      <?= (new Input('qty', ['min' => '1', 'class' => 'form-control', 'id' => 'pi-qty-spin'], 'number'))->default_value('1'); ?>
      
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
