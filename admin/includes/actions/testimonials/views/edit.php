<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  $tID = Text::input($_GET['tID']);

  $testimonials_query = $db->query(sprintf(<<<'EOSQL'
SELECT t.*, td.*
 FROM testimonials t INNER JOIN testimonials_description td ON t.testimonials_id = td.testimonials_id
 WHERE t.testimonials_id = %d
 ORDER BY languages_id = %d DESC, languages_id
 LIMIT 1
EOSQL
    , (int)$tID, (int)$_SESSION['languages_id']));
  $testimonials = $testimonials_query->fetch_assoc();

  $tInfo = new objectInfo($testimonials);
  $link->set_parameter('tID', (string)(int)$tID);

  if (!isset($tInfo->testimonials_status)) {
    $tInfo->testimonials_status = '1';
  }

  $in_status_radio = new Tickable('testimonials_status', ['value' => '1', 'id' => 'inStatus', 'class' => 'form-check-input'], 'radio');
  $out_status_radio = new Tickable('testimonials_status', ['value' => '0', 'id' => 'outStatus', 'class' => 'form-check-input'], 'radio');
  if ('1' === $tInfo->testimonials_status) {
    $in_status_radio->tick();
  } else {
    $out_status_radio->tick();
  }

  $form = new Form('testimonial', $link->set_parameter('action', 'update'), 'post', ['enctype' => 'multipart/form-data']);
  $form->hide('testimonials_id', $tInfo->testimonials_id)
       ->hide('customers_name', $tInfo->customers_name)
       ->hide('date_added', $tInfo->date_added);
?>

  <?= $form ?>

    <div class="row mb-2 align-items-center" id="zStatus">
      <div class="col-form-label col-sm-3 text-start text-sm-end"><?= TEXT_INFO_TESTIMONIAL_STATUS ?></div>
      <div class="col-sm-9">
        <div class="form-check form-check-inline">
          <?= $in_status_radio ?>
          <label class="form-check-label" for="inStatus"><?= TEXT_TESTIMONIAL_PUBLISHED ?></label>
        </div>
        <div class="form-check form-check-inline">
          <?= $out_status_radio ?>
          <label class="form-check-label" for="outStatus"><?= TEXT_TESTIMONIAL_NOT_PUBLISHED ?></label>
        </div>
      </div>
    </div>

    <div class="row mb-2" id="zFrom">
      <label for="inputFrom" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_FROM ?></label>
      <div class="col-sm-9">
        <?= Customers::select('customers_id', ['class' => 'form-select', 'id' => 'inputFrom'], $tInfo->customers_id) ?>
      </div>
    </div>

    <div class="row mb-2" id="zNick">
      <label for="inputNick" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_FROM_NICKNAME ?></label>
      <div class="col-sm-9">
        <?= (new Input('customer_name', ['value' => $tInfo->customers_name, 'id' => 'inputNick']))->require() ?>
      </div>
    </div>

    <div class="row mb-2" id="zText">
      <label for="inputText" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_TESTIMONIAL ?></label>
      <div class="col-sm-9">
        <?= (new Textarea('testimonials_text', ['cols' => '60', 'rows' => '15', 'id' => 'inputText', 'aria-describedby' => 'TextHelp']))->require()->set_text($tInfo->testimonials_text ?? '') ?>
        <small id="TextHelp" class="form-text text-muted"><?= ENTRY_TESTIMONIAL_HTML_DISPLAYED ?></small>
      </div>
    </div>

    <?= $admin_hooks->cat('formEdit') ?>
    
    <div class="d-grid mt-2">
      <?= new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success btn-lg') ?>
    </div>

  </form>
