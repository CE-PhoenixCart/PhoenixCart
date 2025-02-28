<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $display->setParentGroupString('<div class="list-group list-group-flush">', '</div>', false);
  $display->setSpacerString('<i class="fas fa-angle-right ms-2 me-1 text-body-secondary"></i>', 1);
?>
<div class="card mt-2 bm-categories">
  <div class="card-header">
    <?= MODULE_BOXES_CATEGORIES_BOX_TITLE ?>
  </div>
  <div class="list-group list-group-flush">
    <?= $display ?>
  </div>
</div>
