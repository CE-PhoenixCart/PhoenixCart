<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $dir_fs_document_root = rtrim($_POST['DIR_FS_DOCUMENT_ROOT'], '/\\') . '/';
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
          <li class="text-muted"><?= TEXT_WEB_SERVER ?></li>
          <li class="text-success"><strong><?= TEXT_STORE_SETTINGS ?></strong></li>
          <li class="text-muted"><?= TEXT_FINISHED ?></li>
        </ol>
      </div>
      <div class="card-footer">
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="w-100"></div>

<div class="row">
  <div class="col-12 col-sm-9">
    <h2 class="display-4"><?= TEXT_STORE_SETTINGS ?></h2>
    <p class="text-danger pull-right text-right"><?= TEXT_REQUIRED_INFORMATION ?></p>

    <form name="install" id="installForm" action="install.php?step=4" method="post" role="form">

      <div class="form-group row">
        <label for="storeName" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_STORE_NAME ?></label>
        <div class="col-sm-9">
          <?= (new Input('CFG_STORE_NAME', ['id' => 'storeName', 'placeholder' => TEXT_STORE_NAME_PLACEHOLDER]))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_STORE_NAME_EXPLANATION ?>
        </div>
      </div>


      <div class="form-group row">
        <label for="ownerName" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_OWNER_NAME ?></label>
        <div class="col-sm-9">
          <?= (new Input('CFG_STORE_OWNER_NAME', ['id' => 'ownerName', 'placeholder' => TEXT_OWNER_NAME_PLACEHOLDER]))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_OWNER_NAME_EXPLANATION ?>
        </div>
      </div>

      <div class="form-group row">
        <label for="ownerEmail" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_OWNER_EMAIL ?></label>
        <div class="col-sm-9">
          <?= (new Input('CFG_STORE_OWNER_EMAIL_ADDRESS', ['id' => 'ownerEmail', 'placeholder' => TEXT_OWNER_EMAIL_PLACEHOLDER]))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_OWNER_EMAIL_EXPLANATION ?>
        </div>
      </div>

      <div class="form-group row">
        <label for="adminUsername" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_ADMIN_USERNAME ?></label>
        <div class="col-sm-9">
          <?= (new Input('CFG_ADMINISTRATOR_USERNAME', ['id' => 'adminUsername', 'placeholder' => TEXT_ADMIN_USERNAME_PLACEHOLDER]))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_ADMIN_USERNAME_EXPLANATION ?>
        </div>
      </div>

      <div class="form-group row">
        <label for="adminPassword" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_ADMIN_PASSWORD ?></label>
        <div class="col-sm-9">
          <?= (new Input('CFG_ADMINISTRATOR_PASSWORD', ['id' => 'adminPassword']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_ADMIN_PASSWORD_EXPLANATION ?>
        </div>
      </div>

<?php
  if (Path::is_writable($dir_fs_document_root) && Path::is_writable($dir_fs_document_root . 'admin')) {
?>
      <div class="form-group row">
        <label for="adminDir" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_ADMIN_DIRECTORY ?></label>
        <div class="col-sm-9">
          <?= (new Input('CFG_ADMIN_DIRECTORY', ['value' => 'admin', 'id' => 'adminDir']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_ADMIN_DIRECTORY_EXPLANATION ?>
        </div>
      </div>
<?php
  }
?>

      <div class="form-group row">
        <label for="Zulu" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_TIME_ZONE ?></label>
        <div class="col-sm-9">
          <?= (new Select('CFG_TIME_ZONE', Installer::load_time_zones()))->set_default_selection(date_default_timezone_get()),
              TEXT_REQUIRED_INFORMATION,
              TEXT_TIME_ZONE_EXPLANATION ?>
        </div>
      </div>

      <p><?= new Button(TEXT_CONTINUE_STEP_4, 'fas fa-angle-right mr-2', 'btn-success btn-block') ?></p>

      <?php
      foreach ( array_diff_key($_POST, ['x' => 0, 'y' => 1]) as $key => $value ) {
        echo new Input($key, ['value' => $value], 'hidden');
      }
      ?>

    </form>
  </div>
  <div class="col-12 col-sm-3">
    <h3 class="display-4"><?= TEXT_STEP_3 ?></h3>
    <div class="card mb-2 card-body">
      <?= TEXT_STORE_SETTINGS_EXPLANATION ?>
    </div>
  </div>
</div>
