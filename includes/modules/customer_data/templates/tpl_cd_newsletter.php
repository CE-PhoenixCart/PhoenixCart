<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
  <div class="form-group row align-items-center">
    <div class="col-form-label col-sm-3 text-left text-sm-right"><?= ENTRY_NEWSLETTER ?></div>
    <div class="col-sm-9 pl-5 custom-control custom-switch">
      <?= $input->append_css('custom-control-input')->set('id', 'inputNewsletter') ?>
      <label for="inputNewsletter" class="custom-control-label text-muted"><small><?= ENTRY_NEWSLETTER_TEXT ?>&nbsp;</small></label>
    </div>
  </div>
