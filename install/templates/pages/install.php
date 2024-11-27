<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<script>
var dbServer;
var dbUsername;
var dbPassword;
var dbName;
var dbImportSample;

var formSubmitted = false;
var formSuccess = false;

function prepareDB() {
  if (formSubmitted === true) {
    return false;
  }

  formSubmitted = true;

  // Show the mBox
  document.querySelectorAll('.mBox').forEach(function(el) {
    el.style.display = 'block';
  });

  // Set the mBox contents to the "testing database" message
  document.querySelectorAll('.mBoxContents').forEach(function(el) {
    el.innerHTML = '<div class="alert alert-warning">' + <?= json_encode(TEXT_TESTING_DB) ?> + '</div>';
  });

  // Get the form field values
  dbServer = document.getElementById('DB_SERVER').value;
  dbUsername = document.getElementById('DB_SERVER_USERNAME').value;
  dbPassword = document.getElementById('DB_SERVER_PASSWORD').value;
  dbName = document.getElementById('DB_DATABASE').value;
  dbImportSample = document.getElementById('DB_IMPORT_SAMPLE').value;

  // Make the GET request to check the database
  fetch('rpc.php?action=dbCheck&server=' + encodeURIComponent(dbServer) + 
        '&username=' + encodeURIComponent(dbUsername) + 
        '&password=' + encodeURIComponent(dbPassword) + 
        '&name=' + encodeURIComponent(dbName))
    .then(response => response.text())
    .then(response => {
      var result = response.split("|");

      if (result[0] === '1') {
        document.querySelectorAll('.mBoxContents').forEach(function(el) {
          el.innerHTML = '<div class="alert alert-success">' + <?= json_encode(TEXT_IMPORTING_DB) ?> + '</div>';
        });

        // Make the GET request to import the database
        fetch('rpc.php?action=dbImport&server=' + encodeURIComponent(dbServer) + 
              '&username=' + encodeURIComponent(dbUsername) + 
              '&password=' + encodeURIComponent(dbPassword) + 
              '&name=' + encodeURIComponent(dbName) + 
              '&importsample=' + encodeURIComponent(dbImportSample))
          .then(response2 => response2.text())
          .then(response2 => {
            var result2 = response2.split("|");

            if (result2[0] === '1') {
              document.querySelectorAll('.mBoxContents').forEach(function(el) {
                el.innerHTML = '<div class="alert alert-success">' + <?= json_encode(TEXT_DB_SUCCESS) ?> + '</div>';
              });

              formSuccess = true;

              setTimeout(function() {
                document.getElementById('installForm').submit();
              }, 2000);
            } else {
              var result2_error = result2[1];

              document.querySelectorAll('.mBoxContents').forEach(function(el) {
                el.innerHTML = '<div class="alert alert-danger">' + <?= json_encode(TEXT_DB_PROBLEM) ?>.replace('%s', result2_error) + '</div>';
              });

              formSubmitted = false;
            }
          })
          .catch(function() {
            formSubmitted = false;
          });
      } else {
        var result_error = result[1];

        document.querySelectorAll('.mBoxContents').forEach(function(el) {
          el.innerHTML = '<div class="alert alert-danger">' + <?= json_encode(TEXT_DB_CONNECTION_PROBLEM) ?>.replace('%s', result_error) + '</div>';
        });

        formSubmitted = false;
      }
    })
    .catch(function() {
      formSubmitted = false;
    });
  }


  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('installForm').addEventListener('submit', function(e) {
      if (formSuccess === false) {
        e.preventDefault();
        prepareDB();
      }
    });
  });
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
      <ol class="list-group list-group-flush list-group-numbered">
        <li class="list-group-item active"><?= TEXT_DATABASE_SERVER ?></li>
        <li class="list-group-item"><?= TEXT_WEB_SERVER ?></li>
        <li class="list-group-item"><?= TEXT_STORE_SETTINGS ?></li>
        <li class="list-group-item"><?= TEXT_FINISHED ?></li>
      </ol>
      <div class="card-footer">
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" aria-label="<?= sprintf(INSTALLATION_PROGRESS, '25%') ?>" style="width: 25%">25%</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 col-sm-9">

    <div class="mBox">
      <div class="mBoxContents"></div>
    </div>
    
    <h2 class="display-4"><?= TEXT_DATABASE_SERVER ?></h2>

    <form name="install" class="was-validated" id="installForm" action="install.php?step=2" method="post">

      <div class="form-floating mb-3">
        <?= (new Input('DB_SERVER', ['id' => 'DB_SERVER', 'placeholder' => 'localhost']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_DATABASE_ADDRESS ?>
        <label for="DB_SERVER"><?= TEXT_DATABASE_SERVER ?></label>
      </div>
      
      <div class="form-floating mb-3">
        <?= (new Input('DB_SERVER_USERNAME', ['id' => 'DB_SERVER_USERNAME', 'placeholder' => TEXT_USERNAME, 'autocomplete' => 'new-username']))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_USERNAME_DESCRIPTION ?>
        <label for="DB_SERVER_USERNAME"><?= TEXT_USERNAME ?></label>
      </div>
      
      <div class="form-floating mb-3">
        <?= (new Input('DB_SERVER_PASSWORD', ['id' => 'DB_SERVER_PASSWORD', 'autocomplete' => 'new-password'], 'password'))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_PASSWORD_DESCRIPTION ?>
        <label for="DB_SERVER_PASSWORD"><?= TEXT_PASSWORD ?></label>
      </div>
      
      <div class="form-floating mb-3">
        <?= (new Input('DB_DATABASE', ['id' => 'DB_DATABASE', 'placeholder' => TEXT_DATABASE]))->require(),
              TEXT_REQUIRED_INFORMATION,
              TEXT_NAME_DESCRIPTION ?>
        <label for="DB_DATABASE"><?= TEXT_DATABASE_NAME ?></label>
      </div>
      
      <div class="form-floating mb-3">
        <?= (new Select('DB_IMPORT_SAMPLE', [
                 ['id' => '0', 'text' => TEXT_SKIP_SAMPLE_DATA],
                 ['id' => '1', 'text' => TEXT_IMPORT_SAMPLE_DATA]
               ], ['id' => 'DB_IMPORT_SAMPLE']))->set_selection('1'),
              TEXT_REQUIRED_INFORMATION,
              TEXT_SAMPLE_IMPORT_DESCRIPTION ?>
        <label for="DB_IMPORT_SAMPLE"><?= TEXT_IMPORT_SAMPLE_DATA ?></label>
      </div>

      <div class="mBox">
        <div class="mBoxContents"></div>
      </div>

      <p class="d-grid">
        <?= new Button(TEXT_CONTINUE_STEP_2, 'fas fa-angle-right', 'btn-success') ?>
      </p>

    </form>
  </div>
  <div class="col-12 col-sm-3">
    <h2><?= TEXT_STEP_1 ?></h2>
    
    <div class="card card-body">
      <?= TEXT_DB_EXPLANATION ?>
    </div>
  </div>

</div>