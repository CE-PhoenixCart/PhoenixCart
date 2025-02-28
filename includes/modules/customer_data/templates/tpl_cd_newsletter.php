<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  
  <div class="row mb-2 align-items-center">
    <div class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_NEWSLETTER ?></div>
    <div class="col-sm-9">
      <div class="ps-4 form-check">
        <?= $input->set('id', 'inputNewsletter')->append_css('form-check-input') ?>
        <label for="inputNewsletter" class="form-check-label text-body-secondary"><small><?= ENTRY_NEWSLETTER_TEXT ?></small></label>
      </div>
    </div>
  </div>
  
