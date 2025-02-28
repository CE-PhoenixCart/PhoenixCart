<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  <h2 class="fw-normal"><?= BUTTON_EDIT_FAVICON_LOGO ?></h2>

  <div class="row">
    <div class="col">
      <?= new Form('favicon', $Admin->link()->set_parameter('action', 'save_favicon'), 'post', ['enctype' => 'multipart/form-data']) ?>

        <div class="mb-2">
          <?= (new Input('favicon', ['accept' => 'image/*', 'id' => 'inputFavicon', 'class' => 'form-control'], 'file'))->require() ?>

          <label class="form-label" for="inputFavicon"><?= TEXT_LOGO_IMAGE ?></label>
        </div>

        <?= $admin_hooks->cat('editForm') ?>
        
        <div class="d-grid mt-2">
          <?= new Button(IMAGE_UPLOAD, 'fas fa-file-upload', 'btn-success') ?>
        </div>
        
        <p class="mt-2 text-muted"><?= TEXT_LOCATION_FAVICON ?></p>

      </form>
    </div>
    <div class="col">
      <div class="card text-center bg-danger text-white">
        <div class="card-header">
          <?= TEXT_FORMAT ?>
        </div>
        <div class="card-body bg-white py-5">
          <?php
          $array = ['256', '192', '128', '16'];
          foreach ($array as $size) {
            echo $Admin->catalog_image('images/favicon/' .  $size . '_' . FAVICON_LOGO);
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <script>
  var upload = document.querySelector('#inputFavicon');
  if (upload) {
    upload.addEventListener('change', function (event) {
      var labels = document.querySelectorAll('LABEL.form-label');
      for (var i = 0; i < labels.length; i++) {
        if ('inputFavicon' === labels[i].htmlFor) {
          labels[i].innerHTML = event.target.files[0].name;
        }
      }
    });
  }
  </script>