<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

?>

</div>

<footer class="pt-2">
  <div class="bg-light m-0 pt-2 pb-2">
    <div class="<?= BOOTSTRAP_CONTAINER ?>">
      <div class="footer">
        <div class="row">
          <?= $Template->get_content('footer') ?>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-dark text-white pt-3">
    <div class="<?= BOOTSTRAP_CONTAINER ?>">
      <div class="footer-extra">
        <div class="row">
          <?= $Template->get_content('footer_suffix') ?>
        </div>
      </div>
    </div>
  </div>
</footer>
