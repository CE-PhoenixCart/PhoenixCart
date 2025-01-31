<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

const TEXT_TESTING_DB = <<<'EOT'
<div class="row">
  <div class="col-1 d-flex align-items-center">
    <i class="fas fa-spinner fa-spin fa-2x"></i>
  </div>
  <div class="col">
    <p>Testing database connection..</p>
  </div>
</div>
EOT;
const TEXT_IMPORTING_DB = <<<'EOT'
<div class="row">
  <div class="col-1 d-flex align-items-center">
    <i class="fas fa-spinner fa-spin fa-2x"></i>
  </div>
  <div class="col">
    <p>The database structure is now being imported. Please be patient during this procedure.</p>
  </div>
</div>
EOT;
const TEXT_DB_SUCCESS = <<<'EOT'
<div class="row">
  <div class="col-1 d-flex align-items-center">
    <i class="fas fa-thumbs-up fa-2x"></i>
  </div>
  <div class="col">
    <p>Database imported successfully.</p>
  </div>
</div>
EOT;
const TEXT_DB_PROBLEM = <<<'EOT'
<div class="row">
  <div class="col-1 d-flex align-items-center">
    <i class="fas fa-thumbs-down fa-2x text-danger"></i>
  </div>
  <div class="col">
    <p class="text-danger">There was a problem importing the database:</p>
    <p class="text-danger font-monospace">%s</p>
    <p class="text-danger mb-0"><strong>Please double check your server, user & password details and try again.</strong></p>
  </div>
</div>
EOT;
const TEXT_DB_CONNECTION_PROBLEM = <<<'EOT'
<div class="row">
  <div class="col-1 d-flex align-items-center">
    <i class="fas fa-thumbs-down fa-2x text-danger"></i>
  </div>
  <div class="col">
    <p class="text-danger">There was a problem connecting to the database server:</p>
    <p class="text-danger font-monospace">%s</p>
    <p class="text-danger mb-0"><strong>Please double check your server, user & password details and try again.</strong></p>
  </div>
</div>
EOT;
const TEXT_DATABASE_ADDRESS = '<small class="form-text">The address of the database server in the form of a hostname or IP address.</small>';
const TEXT_USERNAME = 'Username';
const TEXT_USERNAME_DESCRIPTION = '<small class="form-text">The username used to connect to the database server.</small>';
const TEXT_PASSWORD = 'Password';
const TEXT_PASSWORD_DESCRIPTION = '<small class="form-text">The password that is used together with the username to connect to the database server.</small>';
const TEXT_DATABASE_NAME = 'Database Name';
const TEXT_NAME_DESCRIPTION = '<small class="form-text">The name of the database to hold the data.  If this database does not exist, Phoenix will attempt to create it.</small><br><small class="form-text text-danger fw-bold">WARNING: If you are using an already existing database, data contained in that database may be lost.</small>';
const TEXT_IMPORT_SAMPLE_DATA = 'Import Sample Data';
const TEXT_SAMPLE_IMPORT_DESCRIPTION = '<small class="form-text">Import sample product and category data?</small>';
const TEXT_CONTINUE_STEP_2 = 'Continue To Step 2';
const TEXT_STEP_1 = 'Step 1';
const TEXT_DB_EXPLANATION = <<<'EOT'
<p>The database server stores data such as product information, customer information, and the orders that have been made.</p>
<p>Please consult your server administrator (host) if your database server parameters are not yet known.</p>
EOT;
const TEXT_DATABASE = 'Database';
const TEXT_SKIP_SAMPLE_DATA = 'Skip sample data';
