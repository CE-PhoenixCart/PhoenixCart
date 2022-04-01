<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  echo $form;
?>
    <div class="form-group row" id="zProduct">
      <label for="reviewProduct" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_PRODUCT ?></label>
      <div class="col-sm-9">
        <?=
        isset($rInfo->products_name)
        ? new Input('products_name', ['value' => $rInfo->products_name, 'readonly' => null, 'class' => 'form-control-plaintext'])
        : Products::select('products_id', ['id' => 'reviewProduct'])->require()
        ?>
      </div>
    </div>

    <div class="form-group row" id="zCustomer">
      <label for="reviewCustomer" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_FROM ?></label>
      <div class="col-sm-9">
        <?=
        isset($rInfo->customers_name)
        ? new Input('customers_name', ['value' => $rInfo->customers_name, 'readonly' => null, 'class' => 'form-control-plaintext'])
        : Customers::select('customer_id', ['id' => 'reviewCustomer'])->require()
        ?>
      </div>
    </div>

    <div class="form-group row" id="zRating">
      <label for="reviewRating" class="col-sm-3 text-left text-sm-right"><?= ENTRY_RATING ?></label>
      <div class="col-sm-9"><div class="form-check form-check-inline">
        <label class="form-check-label font-weight-bold text-danger mr-1" for="rating_1"><?= TEXT_BAD ?></label>
        <?php
        for ($i = 1; $i <= 5; $i++) {
          echo (new Tickable('reviews_rating', ['value' => "$i", 'class' => 'form-check-input', 'id' => "rating_$i"], 'radio'))->tick($i == ($rInfo->reviews_rating ?? 5));
        }
        ?>
        <label class="form-check-label font-weight-bold text-danger" for="rating_5"><?= TEXT_GOOD ?></label></div>
      </div>
    </div>

    <div class="form-group row" id="zReview">
      <label for="reviewReview" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_REVIEW ?></label>
      <div class="col-sm-9"><?= (new Textarea('reviews_text', ['rows' => '5', 'class' => 'form-control']))->require()->set_text($rInfo->reviews_text ?? '') . ENTRY_REVIEW_TEXT ?>
      </div>
    </div>

    <?= $admin_hooks->cat($hook_action) ?>

    <div class="text-right"><?= $button ?></div>

  </form>
