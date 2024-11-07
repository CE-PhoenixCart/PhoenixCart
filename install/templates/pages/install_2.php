<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $www_location = (('on' === getenv('HTTPS')) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
                . (empty($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['SCRIPT_NAME'])['path'] : $_SERVER['REQUEST_URI']);

  $www_location = substr($www_location, 0, strpos($www_location, 'install/install.php'));
  $dir_fs_www_root = Path::normalize(DIR_FS_CATALOG) . '/';
?>


<div class="row">
  <div class="col-sm-9">
    <div class="alert alert-info" role="alert">
      <h1><?= TEXT_NEW_INSTALLATION ?></h1>

      <?= sprintf(TEXT_WEB_INSTALL, Versions::get('Phoenix')) ?>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card mb-2">
      <ol class="list-group list-group-flush list-group-numbered">
        <li class="list-group-item bg-light text-muted"><?= TEXT_DATABASE_SERVER ?></li>
        <li class="list-group-item active"><?= TEXT_WEB_SERVER ?></li>
        <li class="list-group-item"><?= TEXT_STORE_SETTINGS ?></li>
        <li class="list-group-item"><?= TEXT_FINISHED ?></li>
      </ol>
      <div class="card-footer">
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" aria-label="<?= sprintf(INSTALLATION_PROGRESS, '50%') ?>" style="width: 50%">50%</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 col-sm-9">
    
    <h2 class="display-4"><?= TEXT_WEB_SERVER ?></h2>

    <form name="install" class="was-validated" id="installForm" action="install.php?step=3" method="post">

      <div class="form-floating mb-3">
        <?= (new Input('HTTP_WWW_ADDRESS', ['value' => $www_location, 'id' => 'HTTP_WWW_ADDRESS', 'placeholder' => 'https://']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_WWW_ADDRESS_EXPLANATION ?>
        <label for="HTTP_WWW_ADDRESS"><?= TEXT_WWW_ADDRESS ?></label>
      </div>
      
      <div class="form-floating mb-3">
        <?= (new Input('DIR_FS_DOCUMENT_ROOT', ['value' => $dir_fs_www_root, 'id' => 'DIR_FS_DOCUMENT_ROOT']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_WEB_DIRECTORY_EXPLANATION ?>
        <label for="DIR_FS_DOCUMENT_ROOT"><?= TEXT_WEB_DIRECTORY ?></label>
      </div>

      <p class="d-grid"><?= new Button(TEXT_CONTINUE_STEP_3, 'fas fa-angle-right', 'btn-success') ?></p>

      <?php
      foreach ( array_diff_key($_POST, ['x' => 0, 'y' => 1]) as $key => $value ) {
        echo new Input($key, ['value' => $value], 'hidden');
      }
      ?>

    </form>
  </div>
  <div class="col-12 col-sm-3">
    <h3 class="display-4"><?= TEXT_STEP_2 ?></h3>
    
    <div class="card mb-2 card-body">
      <?= TEXT_WEB_SERVER_EXPLANATION ?>
    </div>
  </div>

</div>
