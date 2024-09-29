<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build());

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4 mb-4"><?= HEADING_TITLE ?></h1>

<?= (new Form('account_newsletter', $Linker->build()))->hide('action', 'process') ?>

  <div class="row mb-2 align-items-center">
    <div class="col-form-label col-sm-3 text-start text-sm-end"><?= MY_NEWSLETTERS_GENERAL_NEWSLETTER ?></div>
    <div class="col-sm-9">
      <div class="ps-4 form-check">
        <?= (new Tickable('newsletter_general', ['value' => '1', 'class' => 'form-check-input', 'id' => 'inputNewsletter']))->tick($customer_data->get('newsletter', $newsletter) == '1') ?>
        <label for="inputNewsletter" class="form-check-label text-body-secondary"><small><?= MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION ?></small></label>
      </div>
    </div>
  </div>

  <div class="d-grid">
    <?= new Button(IMAGE_BUTTON_UPDATE_PREFERENCES, 'fas fa-users-cog', 'btn-success btn-lg') ?>
  </div>
  
  <div class="my-2">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
