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
  
  <div class="d-grid"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-trash', 'btn-danger btn-lg btn-block mb-2') ?></div>
  <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?></p>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
