<div class="<?= CU_FORM_CONTENT_WIDTH ?> cu-form">

  <?php
  if (isset($_GET['action']) && ($_GET['action'] === 'success')) {
    printf(FORM_CONTACT_US_SUCCESS, $contact_us_href);
  }
  else {
    echo new Form('contact_us', $GLOBALS['Linker']->build('contact_us.php', ['action' => 'send']), 'post', ['role' => 'form'], true);

    echo FORM_CONTACT_US;
    ?>

    <p class="text-danger text-right"><?= FORM_REQUIRED_INFORMATION ?></p>
    <div class="w-100"></div>

    <div class="form-group row">
      <label for="inputFromName" class="col-sm-3 col-form-label text-right"><?= ENTRY_NAME ?></label>
      <div class="col-sm-9">
        <?= (new Input('name', ['autocomplete' => 'name', 'id' => 'inputFromName', 'placeholder' => ENTRY_NAME_TEXT]))->require(),
            FORM_REQUIRED_INPUT;
        ?>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputFromEmail" class="col-sm-3 col-form-label text-right"><?= ENTRY_EMAIL ?></label>
      <div class="col-sm-9">
        <?= (new Input('email', ['autocomplete' => 'email', 'id' => 'inputFromEmail', 'placeholder' => ENTRY_EMAIL_TEXT], 'email'))->require(),
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
  
    <?= $GLOBALS['hooks']->cat('injectFormDisplay') ?>

    <p><?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-paper-plane', 'btn-success btn-block btn-lg') ?></p>
    
    </form>
  
    <?php
  }
  ?>
  
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
