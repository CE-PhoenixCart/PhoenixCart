<div class="card mb-2 <?= $box['classes'] ?>"<?= $box['attributes'] ?? '' ?>>
  <div class="card-header"><?= $box['title'] ?></div>

  <?php include $GLOBALS['Template']->map(...$box['parameters']) ?>
</div>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>
