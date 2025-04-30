<div class="<?= CU_FORM_CONTENT_WIDTH ?> cu-form">

  <?php
  if (isset($_GET['action']) && ($_GET['action'] === 'success')) {
    printf(FORM_CONTACT_US_SUCCESS, $contact_us_href);
  }
  else {
    echo new Form('contact_us', $GLOBALS['Linker']->build('contact_us.php', ['action' => 'send']), 'post', ['class' => 'was-validated'], true);

    echo FORM_CONTACT_US;
    ?>

    <div class="form-floating mb-2">
      <?= (new Input('name', ['autocomplete' => 'name', 'id' => 'inputFromName', 'placeholder' => ENTRY_NAME_TEXT]))->require(), FORM_REQUIRED_INPUT; ?>
      <label for="inputFromName"><?= ENTRY_NAME ?></label>
    </div>
    
    <div class="form-floating mb-2">
      <?= (new Input('email', ['autocomplete' => 'email', 'id' => 'inputFromEmail', 'placeholder' => ENTRY_EMAIL_TEXT], 'email'))->require(), FORM_REQUIRED_INPUT; ?>
      <label for="inputFromEmail"><?= ENTRY_EMAIL ?></label>
    </div>

    <div class="form-floating mb-2">
      <?= (new Textarea('enquiry', ['style' => 'height: 200px', 'id' => 'inputEnquiry', 'placeholder' => ENTRY_ENQUIRY_TEXT]))->require(), FORM_REQUIRED_INPUT; ?>
      <label for="inputEnquiry"><?= ENTRY_ENQUIRY ?></label>
    </div>
  
    <?= $GLOBALS['hooks']->cat('injectFormDisplay') ?>

    <div class="d-grid">
      <?= new Button(IMAGE_BUTTON_CONTINUE, 'fas fa-paper-plane', 'btn-success btn-lg') ?>
    </div>
    
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
