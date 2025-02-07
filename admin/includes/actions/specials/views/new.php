<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  echo new Form('review', $Admin->link('specials.php', ['action' => 'insert']), 'post');
?>

    <div class="row mb-2" id="zProduct">
      <label for="specialProduct" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_SPECIALS_PRODUCT ?></label>
      <div class="col-sm-9">
        <?= (new Select('products_id', $discountables ?? Products::list_discountable(), ['class' => 'form-select', 'id' => 'specialProduct']))->require() ?>
      </div>
    </div>

    <div class="row mb-2" id="zPrice">
      <label for="specialPrice" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_SPECIALS_SPECIAL_PRICE ?></label>
      <div class="col-sm-9">
        <?= (new Input('specials_price', ['id' => 'specialPrice']))->require() ?>
      </div>
    </div>

    <div class="row mb-2" id="zDate">
      <label for="specialDate" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_SPECIALS_EXPIRES_DATE ?></label>
      <div class="col-sm-9">
        <?= new Input('expdate', ['min' => date('Y-m-d'), 'class' => 'form-control w-25', 'id' => 'specialDate', 'onfocus' => 'this.showPicker?.()'], 'date') ?>
      </div>
    </div>
    
    <div class="alert alert-info">
      <?= TEXT_SPECIALS_PRICE_TIP ?>
    </div>

    <?= $admin_hooks->cat('formNew') ?>
    
    <div class="d-grid mt-2">
      <?= new Button(IMAGE_SAVE, 'fas fa-pen', 'btn-success') ?>
    </div>

  </form>
