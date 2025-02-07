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
    <div class="row mb-2" id="zProduct">
      <label for="reviewProduct" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_PRODUCT ?></label>
      <div class="col-sm-9">
        <?=
        isset($rInfo->products_name)
        ? new Input('products_name', ['value' => $rInfo->products_name, 'readonly' => null, 'class' => 'form-control-plaintext', 'id' => 'reviewProduct'])
        : Products::select('products_id', ['class' => 'form-select', 'id' => 'reviewProduct'])->require()
        ?>
      </div>
    </div>

    <div class="row mb-2" id="zCustomer">
      <label for="reviewCustomer" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_FROM ?></label>
      <div class="col-sm-9">
        <?=
        isset($rInfo->customers_name)
        ? new Input('customers_name', ['value' => $rInfo->customers_name, 'readonly' => null, 'class' => 'form-control-plaintext', 'id' => 'reviewCustomer'])
        : Customers::select('customer_id', ['class' => 'form-select', 'id' => 'reviewCustomer'])->require()
        ?>
      </div>
    </div>

    <div class="row mb-2" id="zRating">
      <div class="col-sm-3 text-start text-sm-end"><?= ENTRY_RATING ?></div>
      <div class="col-sm-9">
        <span class="fw-bold text-danger me-1"><?= TEXT_BAD ?></span>
        <?php
        for ($i = 1; $i <= 5; $i++) {
          echo '<div class="form-check form-check-inline">';
            echo (new Tickable('reviews_rating', ['value' => "$i", 'class' => 'form-check-input', 'id' => "rating_$i"], 'radio'))->tick($i == ($rInfo->reviews_rating ?? 5));
            echo '<label class="form-check-label" for="rating_' . $i . '">' . $i . '</label>';
          echo '</div>';
        }
        ?>
        <span class="fw-bold text-danger"><?= TEXT_GOOD ?></span>
      </div>
    </div>

    <div class="row mb-2" id="zReview">
      <label for="reviewReview" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_REVIEW ?></label>
      <div class="col-sm-9"><?= (new Textarea('reviews_text', ['rows' => '5', 'class' => 'form-control', 'id' => 'reviewReview']))->require()->set_text($rInfo->reviews_text ?? '') . ENTRY_REVIEW_TEXT ?>
      </div>
    </div>

    <?= $admin_hooks->cat($hook_action) ?>

    <div class="text-end"><?= $button ?></div>

  </form>
