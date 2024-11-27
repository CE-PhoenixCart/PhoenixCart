<div class="<?= $content_width ?> cm-t-write">

  <?php
  if ($messageStack->size('testimonial') > 0) {
    echo $messageStack->output('testimonial');
  }
  ?>

  <div class="d-grid">
    <button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTestimonial" aria-expanded="false" aria-controls="collapseTestimonial"><?= MODULE_CONTENT_TESTIMONIALS_WRITE_BUTTON_TEXT ?></button>
  </div>

  <div class="collapse" id="collapseTestimonial">
    <div class="my-3">

      <div class="alert alert-info"><?= MODULE_CONTENT_TESTIMONIALS_WRITE_PUBLIC_TEXT ?></div>

      <?= new Form('write_testimonial', $GLOBALS['Linker']->build('testimonials.php')->retain_query_except()->set_parameter('action', 'testimonial_write'), 'post', ['class' => 'was-validated']) ?>

        <div class="form-floating mb-2">
          <?= (new Input('nickname', ['id' => 'inputNick', 'placeholder' => MODULE_CONTENT_TESTIMONIALS_WRITE_NICKNAME_PLACEHOLDER]))->require(), FORM_REQUIRED_INPUT ?>
          <label for="inputNick"><?= MODULE_CONTENT_TESTIMONIALS_WRITE_NICKNAME_PLACEHOLDER ?></label>
        </div>

        <div class="form-floating mb-2">
          <?= (new Textarea('text', ['style' => 'height: 150px', 'id' => 'inputText', 'placeholder' => MODULE_CONTENT_TESTIMONIALS_WRITE_TEXT_PLACEHOLDER]))->require() . FORM_REQUIRED_INPUT ?>
          <label for="inputText"><?= MODULE_CONTENT_TESTIMONIALS_WRITE_TEXT_PLACEHOLDER ?></label>
        </div>

        <div class="d-grid">
          <?= new Button(MODULE_CONTENT_TESTIMONIALS_WRITE_BUTTON_TEXT_SEND, 'fas fa-paper-plane', 'btn-success btn-lg') ?>
        </div>

      </form>
    </div>
  </div>
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
