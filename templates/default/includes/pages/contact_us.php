<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE, $Linker->build('contact_us.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($messageStack->size('contact') > 0) {
    echo $messageStack->output('contact');
  }

  if (isset($_GET['action']) && ($_GET['action'] === 'success')) {
?>

  <div class="alert alert-info" role="alert"><?= TEXT_SUCCESS ?></div>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-angle-right', 'btn-light btn-block btn-lg', [], $Linker->build('index.php')) ?></div>
  </div>

<?php
  } else {
    echo new Form('contact_us', $Linker->build('contact_us.php', ['action' => 'send']), 'post', [], true);
    ?>

  <div class="row">
    <?= $Template->get_content('contact_us') ?>
  </div>

  <p class="text-danger text-right"><?= FORM_REQUIRED_INFORMATION ?></p>
  <div class="w-100"></div>

  <div class="form-group row">
    <label for="inputFromName" class="col-sm-3 col-form-label text-right"><?= ENTRY_NAME ?></label>
    <div class="col-sm-9">
      <?= (new Input('name', ['id' => 'inputFromName', 'placeholder' => ENTRY_NAME_TEXT]))->require(),
          FORM_REQUIRED_INPUT;
      ?>
    </div>
  </div>

  <div class="form-group row">
    <label for="inputFromEmail" class="col-sm-3 col-form-label text-right"><?= ENTRY_EMAIL ?></label>
    <div class="col-sm-9">
      <?= (new Input('email', ['id' => 'inputFromEmail', 'placeholder' => ENTRY_EMAIL_TEXT], 'email'))->require(),
          FORM_REQUIRED_INPUT;
      ?>
    </div>
  </div>

  <div class="form-group row">
    <label for="inputEnquiry" class="col-sm-3 col-form-label text-right"><?= ENTRY_ENQUIRY ?></label>
    <div class="col-sm-9">
      <?= (new Textarea('enquiry', ['cols' => '50', 'rows' => '15', 'id' => 'inputEnquiry', 'placeholder' => ENTRY_ENQUIRY_TEXT]))->require(),
          FORM_REQUIRED_INPUT;
      ?>
    </div>
  </div>

  <?= $hooks->cat('injectFormDisplay') ?>

  <div class="buttonSet">
    <div class="text-right"><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-paper-plane', 'btn-success btn-block btn-lg') ?></div>
  </div>

</form>

<?php
  }

  require $Template->map('template_bottom.php', 'component');
?>
