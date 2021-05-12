<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

const TEXT_TESTING_DB = '<i class="fas fa-spinner fa-spin fa-2x"></i> Testing database connection..';
const TEXT_IMPORTING_DB = '<i class="fas fa-spinner fa-spin fa-2x"></i> The database structure is now being imported. Please be patient during this procedure.';
const TEXT_DB_SUCCESS = '<i class="fas fa-thumbs-up fa-2x"></i> Database imported successfully.';
const TEXT_DB_PROBLEM = <<<'EOT'
<p><i class="fas fa-thumbs-down fa-2x text-danger"></i> There was a problem importing the database. The following error had occured:</p>
<p  class="text-danger"><strong>%s</strong></p>
<p class="text-danger">Please verify the connection parameters and try again.</p>
EOT;
const TEXT_DB_CONNECTION_PROBLEM = <<<'EOT'
<p><i class="fas fa-thumbs-down fa-2x text-danger"></i> There was a problem connecting to the database server. The following error had occured:</p>
<p class="text-danger"><strong>%s</strong></p>
<p class="text-danger">Please verify the connection parameters and try again.</p>
EOT;
const TEXT_DATABASE_ADDRESS = '<small class="form-text text-muted">The address of the database server in the form of a hostname or IP address.</small>';
const TEXT_USERNAME = 'Username';
const TEXT_USERNAME_DESCRIPTION = '<small class="form-text text-muted">The username used to connect to the database server.</small>';
const TEXT_PASSWORD = 'Password';
const TEXT_PASSWORD_DESCRIPTION = '<small class="form-text text-muted">The password that is used together with the username to connect to the database server.</small>';
const TEXT_DATABASE_NAME = 'Database Name';
const TEXT_NAME_DESCRIPTION = '<small class="form-text text-muted">The name of the database to hold the data.</small>';
const TEXT_IMPORT_SAMPLE_DATA = 'Import Sample Data';
const TEXT_SAMPLE_IMPORT_DESCRIPTION = '<small class="form-text text-muted">Import sample product and category data?</small>';
const TEXT_CONTINUE_STEP_2 = 'Continue To Step 2';
const TEXT_STEP_1 = 'Step 1';
const TEXT_DB_EXPLANATION = <<<'EOT'
<p>The database server stores data such as product information, customer information, and the orders that have been made.</p>
<p>Please consult your server administrator (host) if your database server parameters are not yet known.</p>
EOT;
const TEXT_DATABASE = 'Database';
const TEXT_SKIP_SAMPLE_DATA = 'Skip sample data';
