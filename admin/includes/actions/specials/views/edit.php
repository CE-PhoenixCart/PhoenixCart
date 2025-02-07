<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $sID = (int)$_GET['sID'];

  $product_query = $db->query(sprintf(<<<'EOSQL'
SELECT p.*, pd.*, s.*
 FROM products p INNER JOIN products_description pd ON p.products_id = pd.products_id and pd.language_id = %d INNER JOIN specials s ON pd.products_id = s.products_id
 WHERE s.specials_id = %d
EOSQL
        , (int)$_SESSION['languages_id'], $sID));
  $product = $product_query->fetch_assoc();

  $sInfo = new objectInfo($product);
  $link->set_parameter('sID', (string)(int)$sID);

  $form = new Form('new_special', $link->set_parameter('action', 'update'), 'post');
  $form->hide('specials_id', $sID)
       ->hide('products_price', $sInfo->products_price ?? '');
?>

  <?= $form ?>

    <div class="row mb-2" id="zProduct">
      <label for="specialProduct" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_SPECIALS_PRODUCT ?></label>
      <div class="col-sm-9">
        <?= new Input('n', ['value' => $sInfo->products_name . ' (' . $currencies->format($sInfo->products_price) . ')', 'readonly' => null, 'class' => 'form-control-plaintext']) ?>
      </div>
    </div>

    <div class="row mb-2" id="zPrice">
      <label for="specialPrice" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_SPECIALS_SPECIAL_PRICE ?></label>
      <div class="col-sm-9">
        <?= (new Input('specials_price', ['value' => ($sInfo->specials_new_products_price ?? ''), 'id' => 'specialPrice']))->require() ?>
      </div>
    </div>

    <div class="row mb-2" id="zDate">
      <label for="specialDate" class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_SPECIALS_EXPIRES_DATE ?></label>
      <div class="col-sm-9">
        <?= new Input('expdate', ['min' => date('Y-m-d'), 'class' => 'form-control w-25', 'value' => substr($sInfo->expires_date ?? '', 0, 10), 'id' => 'specialDate', 'onfocus' => 'this.showPicker?.()'], 'date') ?>
      </div>
    </div>

    <div class="alert alert-info">
      <?= TEXT_SPECIALS_PRICE_TIP ?>
    </div>

    <?= $admin_hooks->cat('formEdit') ?>
    
    <div class="d-grid mt-2">
      <?= new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-lg') ?>
    </div>

  </form>
