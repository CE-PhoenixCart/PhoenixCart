<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <div class="alert alert-info">
    <?= TEXT_EXPORT_INTRO ?>
  </div>

  <?= (new Textarea('server configuration', ['cols' => '100', 'rows' => '15']))->set_text("$system_info") ?>
  
  <div class="d-grid mt-2">
    <?= Admin::button(BUTTON_SAVE_TO_DISK, 'fas fa-save', 'btn-success btn-lg', $Admin->link('server_info.php', ['action' => 'save'])) ?>
  </div>

