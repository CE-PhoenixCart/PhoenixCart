<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<div class="modal fade" id="<?= $modal['name'] ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $modal['name'] ?>Label" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="<?= $modal['name'] ?>Label"><?= $modal['title'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= IMAGE_BUTTON_CLOSE ?>"></button>
      </div>
      <div class="modal-body">
        <?= $modal['text'] ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $modal['close_button'] ?></button>
      </div>
    </div>
  </div>
</div>
