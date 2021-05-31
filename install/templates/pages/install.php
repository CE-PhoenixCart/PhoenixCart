<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<script>
<!--

  var dbServer;
  var dbUsername;
  var dbPassword;
  var dbName;
  var dbImportSample;

  var formSubmited = false;
  var formSuccess = false;

  function prepareDB() {
    if (formSubmited == true) {
      return false;
    }

    formSubmited = true;

    $('.mBox').show();

    $('.mBoxContents').html('<div class="alert alert-warning">' + <?= json_encode(TEXT_TESTING_DB) ?> + '</div>');

    dbServer = $('#DB_SERVER').val();
    dbUsername = $('#DB_SERVER_USERNAME').val();
    dbPassword = $('#DB_SERVER_PASSWORD').val();
    dbName = $('#DB_DATABASE').val();
    dbImportSample = $('#DB_IMPORT_SAMPLE').val();

    $.get('rpc.php?action=dbCheck&server=' + encodeURIComponent(dbServer) + '&username=' + encodeURIComponent(dbUsername) + '&password=' + encodeURIComponent(dbPassword) + '&name=' + encodeURIComponent(dbName), function (response) {
      var result = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(response);
      result.shift();

      if (result[0] == '1') {
        $('.mBoxContents').html('<div class="alert alert-success">' + <?= json_encode(TEXT_IMPORTING_DB) ?> + '</div>');

        $.get('rpc.php?action=dbImport&server=' + encodeURIComponent(dbServer) + '&username=' + encodeURIComponent(dbUsername) + '&password='+ encodeURIComponent(dbPassword) + '&name=' + encodeURIComponent(dbName) + '&importsample=' + encodeURIComponent(dbImportSample), function (response2) {
          var result2 = /\[\[([^|]*?)(?:\|([^|]*?)){0,1}\]\]/.exec(response2);
          result2.shift();

          if (result2[0] == '1') {
            $('.mBoxContents').html('<div class="alert alert-success">' + <?= json_encode(TEXT_DB_SUCCESS) ?> + '</div>');

            formSuccess = true;

            setTimeout(function() {
              $('#installForm').submit();
            }, 2000);
          } else {
            var result2_error = result2[1].replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

            $('.mBoxContents').html('<div class="alert alert-danger">' + <?= json_encode(TEXT_DB_PROBLEM) ?>.replace('%s', result2_error) + '</div>');

            formSubmited = false;
          }
        }).fail(function() {
          formSubmited = false;
        });
      } else {
        var result_error = result[1].replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

        $('.mBoxContents').html('<div class="alert alert-danger">' + <?= json_encode(TEXT_DB_CONNECTION_PROBLEM) ?>.replace('%s', result_error) + '</div>');

        formSubmited = false;
      }
    }).fail(function() {
      formSubmited = false;
    });
  }

  $(function() {
    $('#installForm').submit(function(e) {
      if ( formSuccess == false ) {
        e.preventDefault();

        prepareDB();
      }
    });
  });

//-->
</script>
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
          <li class="text-success"><strong><?= TEXT_DATABASE_SERVER ?></strong></li>
          <li class="text-muted"><?= TEXT_WEB_SERVER ?></li>
          <li class="text-muted"><?= TEXT_STORE_SETTINGS ?></li>
          <li class="text-muted"><?= TEXT_FINISHED ?></li>
        </ol>
      </div>
      <div class="card-footer">
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%">25%</div>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="w-100"></div>

<div class="row">
  <div class="col-12 col-sm-9">

    <div class="mBox">
      <div class="mBoxContents"></div>
    </div>

    <h2 class="display-4"><?= TEXT_DATABASE_SERVER ?></h2>
    <p class="text-danger pull-right text-right"><?= TEXT_REQUIRED_INFORMATION ?></p>

    <form name="install" id="installForm" action="install.php?step=2" method="post" role="form">

      <div class="form-group row">
        <label for="dbServer" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_DATABASE_SERVER ?></label>
        <div class="col-sm-9">
          <?= (new Input('DB_SERVER', ['id' => 'DB_SERVER', 'placeholder' => 'localhost']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_DATABASE_ADDRESS ?>
        </div>
      </div>

      <div class="form-group row">
        <label for="userName" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_USERNAME ?></label>
        <div class="col-sm-9">
          <?= (new Input('DB_SERVER_USERNAME', ['id' => 'DB_SERVER_USERNAME', 'placeholder' => TEXT_USERNAME]))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_USERNAME_DESCRIPTION ?>
        </div>
      </div>

      <div class="form-group row">
        <label for="passWord" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_PASSWORD ?></label>
        <div class="col-sm-9">
          <?= (new Input('DB_SERVER_PASSWORD', ['id' => 'DB_SERVER_PASSWORD'], 'password'))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_PASSWORD_DESCRIPTION ?>
        </div>
      </div>

      <div class="form-group row">
        <label for="dbName" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_DATABASE_NAME ?></label>
        <div class="col-sm-9">
          <?= (new Input('DB_DATABASE', ['id' => 'DB_DATABASE', 'placeholder' => TEXT_DATABASE]))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_NAME_DESCRIPTION ?>
        </div>
      </div>

      <div class="form-group row">
        <label for="dbName" class="col-form-label col-sm-3 text-left text-sm-right"><?= TEXT_IMPORT_SAMPLE_DATA ?></label>
        <div class="col-sm-9">
          <?= (new Select('DB_IMPORT_SAMPLE', [
                 ['id' => '0', 'text' => TEXT_SKIP_SAMPLE_DATA],
                 ['id' => '1', 'text' => TEXT_IMPORT_SAMPLE_DATA]
               ], ['id' => 'DB_IMPORT_SAMPLE']))->set_selection('1'),
              TEXT_REQUIRED_INFORMATION,
              TEXT_SAMPLE_IMPORT_DESCRIPTION ?>
        </div>
      </div>

      <div class="mBox">
        <div class="mBoxContents"></div>
      </div>

      <p><?= new Button(TEXT_CONTINUE_STEP_2, 'fas fa-angle-right mr-2', 'btn-success btn-block') ?></p>

    </form>
  </div>
  <div class="col-12 col-sm-3">
    <h2><?= TEXT_STEP_1 ?></h2>
    <div class="card card-body">
      <?= TEXT_DB_EXPLANATION ?>
    </div>
  </div>

</div>
