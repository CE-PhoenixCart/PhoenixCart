<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

?>

<footer class="pt-2">
  <div class="m-0 py-2 bg-body-tertiary border-top footer">
    <div class="<?= BOOTSTRAP_CONTAINER ?>">
      <div class="row">
        <?= $Template->get_content('footer') ?>
      </div>
    </div>
  </div>
  <div class="pt-3 bg-body-secondary border-top footer-suffix">
    <div class="<?= BOOTSTRAP_CONTAINER ?>">
      <div class="row">
        <?= $Template->get_content('footer_suffix') ?>
      </div>
    </div>
  </div>
</footer>
