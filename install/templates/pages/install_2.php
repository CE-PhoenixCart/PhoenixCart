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
      <div class="card-body">
        <ol>
          <li class="text-muted"><?= TEXT_DATABASE_SERVER ?></li>
          <li class="text-success"><strong><?= TEXT_WEB_SERVER ?></strong></li>
          <li class="text-muted"><?= TEXT_STORE_SETTINGS ?></li>
          <li class="text-muted"><?= TEXT_FINISHED ?></li>
        </ol>
      </div>
      <div class="card-footer">
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">50%</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="w-100"></div>

<div class="row">
  <div class="col-12 col-sm-9">
    <h2 class="display-4"><?= TEXT_WEB_SERVER ?></h2>
    <p class="text-danger pull-right text-right"><?= TEXT_REQUIRED_INFORMATION ?></p>

    <form name="install" id="installForm" action="install.php?step=3" method="post" role="form">

      <div class="form-group row">
        <label for="wwwAddress" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_WWW_ADDRESS ?></label>
        <div class="col-sm-9">
          <?= (new Input('HTTP_WWW_ADDRESS', ['value' => $www_location, 'id' => 'wwwAddress', 'placeholder' => 'https://']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_WWW_ADDRESS_EXPLANATION ?>
        </div>
      </div>

      <div class="form-group row">
        <label for="webRoot" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_WEB_DIRECTORY ?></label>
        <div class="col-sm-9">
          <?= (new Input('DIR_FS_DOCUMENT_ROOT', ['value' => $dir_fs_www_root, 'id' => 'webRoot']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_WEB_DIRECTORY_EXPLANATION ?>
        </div>
      </div>

      <p><?= new Button(TEXT_CONTINUE_STEP_3, 'fas fa-angle-right mr-2', 'btn-success btn-block') ?></p>

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
