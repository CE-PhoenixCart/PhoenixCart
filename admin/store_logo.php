<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  require 'includes/segments/process_action.php';

  if (!Path::is_writable(DIR_FS_CATALOG . 'images/')) {
    $messageStack->add(sprintf(ERROR_IMAGES_DIRECTORY_NOT_WRITEABLE, $Admin->link('sec_dir_permissions.php')), 'error');
  }

  require 'includes/template_top.php';
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= HEADING_TITLE ?></h1>
    </div>
    <div class="col-12 col-lg-4 text-left text-lg-right align-self-center pb-1">
      <?=
      $Admin->button('<img src="images/icon_phoenix.png" class="mr-2">' . GET_HELP, '', 'btn-dark', GET_HELP_LINK, ['newwindow' => true])
      ?>
    </div>
  </div>

  <?= $Admin->catalog_image('images/' .  STORE_LOGO) ?>
  <small><?= DIR_FS_CATALOG . 'images/' .  STORE_LOGO ?></small>
  <hr>
  
  <h3 class="display-4 mb-2"><?= HEADING_NEW_LOGO ?></h3>
    
  <div class="row">
    <div class="col">
      <?= new Form('logo', $Admin->link()->set_parameter('action', 'save'), 'post', ['enctype' => 'multipart/form-data']) ?>

        <div class="custom-file mb-2">
          <?= (new Input('store_logo', ['accept' => 'image/*', 'id' => 'inputLogo', 'class' => 'custom-file-input'], 'file'))->require() ?>

          <label class="custom-file-label" for="inputLogo"><?= TEXT_LOGO_IMAGE ?></label>
        </div>

        <?= $admin_hooks->cat('editForm'),
            new Button(IMAGE_UPLOAD, 'fas fa-file-upload', 'btn-danger btn-block')
        ?>

      </form>
    </div>
    <div class="col">
      <div class="alert alert-danger"><?= TEXT_FORMAT_AND_LOCATION ?></div>
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

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>
