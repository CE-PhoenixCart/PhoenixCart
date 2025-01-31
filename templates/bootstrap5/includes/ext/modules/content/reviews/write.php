<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $form_link = $Linker->build('ext/modules/content/reviews/write.php')->retain_query_except();
  $breadcrumb->add(NAVBAR_TITLE, $form_link);

  require $Template->map('template_top.php', 'component');
?>

<div class="row mb-4">
  <h1 class="display-4 col-sm-8"><?= $product->get('name') ?></h1>
  <h2 class="display-4 col-sm-4 text-start text-sm-end"><?= $product->hype_price() ?></h2>
</div>

<div class="row">
  <div class="col-md-8">
    <?= new Form('review', (clone $form_link)->set_parameter('action', 'process'), 'post', ['class' => 'was-validated', 'enctype' => 'multipart/form-data']) ?>

    <div class="alert alert-warning" role="alert">
      <?= sprintf(TEXT_REVIEW_WRITING, htmlspecialchars($customer->get('short_name')), $product->get('name')) ?>
    </div>
    
    <div class="form-floating mb-2">
      <?= (new Input('nickname', ['value' => htmlspecialchars($customer->get('short_name')), 'id' => 'inputNick', 'placeholder' => SUB_TITLE_REVIEW_NICKNAME]))->require(), FORM_REQUIRED_INPUT ?>
      <label for="inputNick"><?= SUB_TITLE_FROM ?></label>
    </div>
      
    <div class="form-floating mb-2">
      <?= (new Textarea('review', ['style' => 'height: 150px', 'id' => 'inputText', 'placeholder' => SUB_TITLE_REVIEW_TEXT]))->require() . FORM_REQUIRED_INPUT ?>
      <label for="inputText"><?= SUB_TITLE_REVIEW ?></label>
    </div>

    <div class="row mb-2 align-items-center">
      <label class="col-form-label col-sm-3 text-start text-sm-end"><?= SUB_TITLE_RATING ?></label>
      <div class="col-sm-9">
        <div class="rating d-flex justify-content-end flex-row-reverse align-items-baseline">
          <?= sprintf(TEXT_GOOD, 5) ?>
          <input type="radio" id="r5" name="rating" required aria-required="true" value="5"><label title="<?= sprintf(TEXT_RATED, sprintf(TEXT_GOOD, 5)) ?>" for="r5">&nbsp;</label>
          <input type="radio" id="r4" name="rating" value="4"><label title="<?= sprintf(TEXT_RATED, 4) ?>" for="r4">&nbsp;</label>
          <input type="radio" id="r3" name="rating" value="3"><label title="<?= sprintf(TEXT_RATED, 3) ?>" for="r3">&nbsp;</label>
          <input type="radio" id="r2" name="rating" value="2"><label title="<?= sprintf(TEXT_RATED, 2) ?>" for="r2">&nbsp;</label>
          <input type="radio" id="r1" name="rating" checked value="1"><label title="<?= sprintf(TEXT_RATED, sprintf(TEXT_BAD, 1)) ?>" for="r1">&nbsp;</label>
        </div>
      </div>
    </div>

    <?= $hooks->cat('injectFormDisplay') ?>

    <div class="d-grid">
      <?= new Button(IMAGE_BUTTON_ADD_REVIEW, 'fas fa-pen', 'btn-success btn-lg') ?>
    </div>
    <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light mt-2', [], $Linker->build('product_info.php', ['products_id' => (int)$_GET['products_id']])) ?></p>
    </form>
  </div>
  <div class="col-md-4">
    <?= new Image('images/' . $product->get('image'), [], htmlspecialchars($product->get('name'))) ?>
  </div>
</div>



<?php
  require $Template->map('template_bottom.php', 'component');
?>
