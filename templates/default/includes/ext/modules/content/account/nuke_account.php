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

<?= (new Form('account_destroy', $Linker->build('ext/modules/content/account/nuke_account.php'), 'post', ['class' => 'form-horizontal']))->hide('action', 'process') ?>

<div class="contentContainer">
  <div class="alert alert-danger"><?= MODULE_CONTENT_ACCOUNT_GDPR_NUKE_TEXT ?></div>

  <div class="form-group row">
    <div class="col-form-label col-sm-3 text-left text-sm-right"><?= MODULE_CONTENT_ACCOUNT_GDPR_NUKE_TICKBOX ?></div>
    <div class="col-sm-9 custom-control custom-switch">
      <?= (new Tickable('nuke', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'inputNuke'], 'checkbox'))->require(),
          '<label for="inputNuke" class="custom-control-label text-muted"><small>' . MODULE_CONTENT_ACCOUNT_GDPR_NUKE_HELPER_TEXT . '</small></label>'
      ?>
    </div>
  </div>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-trash', 'btn-danger btn-lg btn-block mb-2') ?></div>
    <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?></p>
  </div>

</div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
