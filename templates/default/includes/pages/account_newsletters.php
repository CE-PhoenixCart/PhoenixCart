<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build());

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?= (new Form('account_newsletter', $Linker->build()))->hide('action', 'process') ?>

  <div class="form-group row align-items-center">
    <div class="col-form-label col-sm-4 text-left text-sm-right"><?= MY_NEWSLETTERS_GENERAL_NEWSLETTER ?></div>
    <div class="col-sm-8 pl-5 custom-control custom-switch"><?=
      (new Tickable('newsletter_general', ['value' => '1', 'class' => 'custom-control-input', 'id' => 'inputNewsletter']))->tick($customer_data->get('newsletter', $newsletter) == '1'),
      '<label for="inputNewsletter" class="custom-control-label text-muted"><small>', MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION, '&nbsp;</small></label>'
    ?></div>
  </div>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_UPDATE_PREFERENCES, 'fas fa-users-cog', 'btn-success btn-lg btn-block') ?></div>
    <p><?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', '', [], $Linker->build('account.php')) ?></p>
  </div>

</form>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
