<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

  echo new Form('review', $Admin->link('testimonials.php', ['action' => 'add_new']), 'post', ['enctype' => 'multipart/form-data']);
?>

    <div class="form-group row" id="zFrom">
      <label for="inputFrom" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_FROM ?></label>
      <div class="col-sm-9">
        <?= Customers::select('customers_id', ['id' => 'inputFrom']) ?>
      </div>
    </div>

    <div class="form-group row" id="zNick">
      <label for="inputNick" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_FROM_NICKNAME ?></label>
      <div class="col-sm-9">
        <?= (new Input('customer_name', ['id' => 'inputNick']))->require() ?>
      </div>
    </div>

    <div class="form-group row" id="zText">
      <label for="inputText" class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_TESTIMONIAL ?></label>
      <div class="col-sm-9">
        <?= (new Textarea('testimonials_text', ['cols' => '60', 'rows' => '15', 'id' => 'inputText', 'aria-describedby' => 'TextHelp']))->require() ?>
        <small id="TextHelp" class="form-text text-muted"><?= ENTRY_TESTIMONIAL_HTML_DISPLAYED ?></small>
      </div>
    </div>

    <?=
      $admin_hooks->cat('formNew'),
      new Button(IMAGE_SAVE, 'fas fa-pen', 'btn-success btn-block btn-lg')
    ?>

  </form>
