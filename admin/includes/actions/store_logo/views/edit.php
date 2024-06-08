<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <h2 class="font-weight-normal"><?= BUTTON_EDIT_LOGO ?></h2>

  <div class="row">
    <div class="col">
      <?= new Form('logo', $Admin->link()->set_parameter('action', 'save'), 'post', ['enctype' => 'multipart/form-data']) ?>

        <div class="custom-file mb-2">
          <?= (new Input('store_logo', ['accept' => 'image/*', 'id' => 'inputLogo', 'class' => 'custom-file-input'], 'file'))->require() ?>

          <label class="custom-file-label" for="inputLogo"><?= TEXT_LOGO_IMAGE ?></label>
        </div>

        <?= $admin_hooks->cat('editForm'),
            new Button(IMAGE_UPLOAD, 'fas fa-file-upload', 'btn-success btn-block')
        ?>
        
        <p class="mt-2 text-muted"><?= TEXT_LOCATION ?></p>

      </form>
    </div>
    <div class="col">
      <div class="card text-center bg-danger text-white">
        <div class="card-header">
          <?= TEXT_FORMAT ?>
        </div>
        <div class="card-body bg-white py-5">
          <?= $Admin->catalog_image('images/' .  STORE_LOGO) ?>
        </div>
      </div>
    </div>
  </div>

  <script>
  var upload = document.querySelector('#inputLogo');
  if (upload) {
    upload.addEventListener('change', function (event) {
      var labels = document.querySelectorAll('LABEL.custom-file-label');
      for (var i = 0; i < labels.length; i++) {
        if ('inputLogo' === labels[i].htmlFor) {
          labels[i].innerHTML = event.target.files[0].name;
        }
      }
    });
  }
  </script>