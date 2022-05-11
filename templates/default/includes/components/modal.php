<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<div class="modal fade" id="<?= $modal['name'] ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $modal['name'] ?>Label" aria-hidden="true">
  <div class="modal-dialog" role="document"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="<?= $modal['name'] ?>Label"><?= $modal['title'] ?></h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="modal-body"><?= $modal['text'] ?></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= $modal['close_button'] ?></button></div>
  </div></div>
</div>
