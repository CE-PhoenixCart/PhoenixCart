<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <h2 class="fw-normal"><?= BUTTON_EDIT_MINI_LOGO ?></h2>

  <div class="row">
    <div class="col">
      <?= new Form('logo', $Admin->link()->set_parameter('action', 'save_mini'), 'post', ['enctype' => 'multipart/form-data']) ?>

        <div class="mb-2">
          <?= (new Input('mini_logo', ['accept' => 'image/*', 'id' => 'inputMiniLogo', 'class' => 'form-control'], 'file'))->require() ?>

          <label class="form-label" for="inputMiniLogo"><?= TEXT_LOGO_IMAGE ?></label>
        </div>

        <?= $admin_hooks->cat('editForm') ?>
        
        <div class="d-grid mt-2">
          <?= new Button(IMAGE_UPLOAD, 'fas fa-file-upload', 'btn-success') ?>
        </div>
        
        <p class="mt-2 text-muted"><?= TEXT_LOCATION ?></p>

      </form>
    </div>
    <div class="col">
      <div class="card text-center bg-danger text-white">
        <div class="card-header">
          <?= TEXT_FORMAT ?>
        </div>
        <div class="card-body bg-white py-5">
          <?= $Admin->catalog_image('images/' .  MINI_LOGO) ?>
        </div>
      </div>
    </div>
  </div>

  <script>
  var upload = document.querySelector('#inputMiniLogo');
  if (upload) {
    upload.addEventListener('change', function (event) {
      var labels = document.querySelectorAll('LABEL.form-label');
      for (var i = 0; i < labels.length; i++) {
        if ('inputMiniLogo' === labels[i].htmlFor) {
          labels[i].innerHTML = event.target.files[0].name;
        }
      }
    });
  }
  </script>