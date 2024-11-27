<div class="<?= $content_width ?> cm-t-write">

  <?php
  if ($messageStack->size('testimonial') > 0) {
    echo $messageStack->output('testimonial');
  }
  ?>

  <button class="btn btn-light btn-block" type="button" data-toggle="collapse" data-target="#collapseTestimonial" aria-expanded="false" aria-controls="collapseTestimonial"><?= MODULE_CONTENT_TESTIMONIALS_WRITE_BUTTON_TEXT ?></button>

  <div class="collapse" id="collapseTestimonial">
    <div class="my-3">

      <div class="alert alert-info"><?= MODULE_CONTENT_TESTIMONIALS_WRITE_PUBLIC_TEXT ?></div>

      <?= new Form('write_testimonial', $GLOBALS['Linker']->build('testimonials.php')->retain_query_except()->set_parameter('action', 'testimonial_write'), 'post') ?>

        <div class="form-group row">
          <label for="inputNick" class="col-sm-3 col-form-label text-right"><?= MODULE_CONTENT_TESTIMONIALS_WRITE_NICKNAME ?></label>
          <div class="col-sm-9">
            <?= (new Input('nickname', ['id' => 'inputNick', 'placeholder' => MODULE_CONTENT_TESTIMONIALS_WRITE_NICKNAME_PLACEHOLDER]))->require() . FORM_REQUIRED_INPUT ?>
          </div>
        </div>

        <div class="form-group row">
          <label for="inputText" class="col-sm-3 col-form-label text-right"><?= MODULE_CONTENT_TESTIMONIALS_WRITE_TEXT ?></label>
          <div class="col-sm-9">
            <?= (new Textarea('text', ['cols' => '50', 'rows' => '15', 'id' => 'inputText', 'placeholder' => MODULE_CONTENT_TESTIMONIALS_WRITE_TEXT_PLACEHOLDER]))->require() . FORM_REQUIRED_INPUT ?>
          </div>
        </div>

        <?= new Button(MODULE_CONTENT_TESTIMONIALS_WRITE_BUTTON_TEXT_SEND, 'fas fa-paper-plane', 'btn-success btn-block btn-lg') ?>

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
