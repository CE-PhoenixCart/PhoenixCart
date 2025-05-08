<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_GDPR_NUKE_NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(MODULE_CONTENT_ACCOUNT_GDPR_NUKE_NAVBAR_TITLE_2, $Linker->build('ext/modules/content/account/nuke_account.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= MODULE_CONTENT_ACCOUNT_GDPR_NUKE_HEADING_TITLE ?></h1>

<?php
if ($messageStack->size('nuke') > 0) {
  echo $messageStack->output('nuke');
}
?>

<div class="col-sm-6 offset-sm-3">

<?= (new Form('account_destroy', $Linker->build('ext/modules/content/account/nuke_account.php'), 'post', ['class' => 'was-validated']))->hide('action', 'process') ?>

  <div class="alert alert-danger"><?= MODULE_CONTENT_ACCOUNT_GDPR_NUKE_TEXT ?></div>

  <div class="row mb-2">
    <div class="col-sm-3 text-start text-sm-end"><?= MODULE_CONTENT_ACCOUNT_GDPR_NUKE_TICKBOX ?></div>
    <div class="col-sm-9">
      <div class="form-check">
        <?= (new Tickable('nuke', ['value' => '1', 'class' => 'form-check-input', 'id' => 'inputNuke'], 'checkbox'))->require(),
          '<label for="inputNuke" class="form-check-label text-body-secondary"><small>' . MODULE_CONTENT_ACCOUNT_GDPR_NUKE_HELPER_TEXT . '</small></label>'
        ?>
      </div>
    </div>
  </div>
  
  <?php
  $input_id = 'inputCurrent';
  $label_text = ENTRY_PASSWORD_CURRENT;
  $input = (new Input('password', [
    'autocapitalize' => 'none',
    'id' => $input_id,
    'autocomplete' => 'current-password',
    'placeholder' => ENTRY_PASSWORD_CURRENT_TEXT,
  ], 'password'))->require() . FORM_REQUIRED_INPUT;

  include $Template->map('includes/modules/customer_data/cd_whole_row_input.php');

  $customer_data->display_input($input);
  ?>

  <div class="d-grid mb-2"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-trash', 'btn-danger btn-lg') ?></div>
  <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?></p>

</form>

</div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
