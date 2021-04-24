<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

const HEADING_TITLE = 'Administrators';

const TABLE_HEADING_ADMINISTRATORS = 'Administrators';
const TABLE_HEADING_HTPASSWD = 'Secured by htpasswd';
const TABLE_HEADING_ACTION = 'Action';

const TEXT_INFO_INSERT_INTRO = 'Please enter the new administrator with its related data';
const TEXT_INFO_EDIT_INTRO = 'Please make any necessary changes';
const TEXT_INFO_DELETE_INTRO = 'Are you sure you want to delete this administrator?';
const TEXT_INFO_HEADING_NEW_ADMINISTRATOR = 'New Administrator';
const TEXT_INFO_USERNAME = 'Username:';
const TEXT_INFO_NEW_PASSWORD = 'New Password:';
const TEXT_INFO_PASSWORD = 'Password:';
const TEXT_INFO_PROTECT_WITH_HTPASSWD = 'Protect With htaccess/htpasswd';
const TEXT_HTPASSWRD_NA_IIS = 'N/A';

const ERROR_ADMINISTRATOR_EXISTS = '<strong>Error:</strong> Administrator already exists.';

const HTPASSWD_INFO = <<<'EOT'
<strong>Additional Protection With htaccess/htpasswd</strong>
<p>This CE Phoenix Administration Tool installation is not additionally secured through htaccess/htpasswd means.</p>
<p>Enabling the htaccess/htpasswd security layer will automatically store administrator username and passwords in an htpasswd file when updating administrator password records.</p>
<p><strong>Please note</strong>, if this additional security layer is enabled and you can no longer access the Administration Tool,
 please make the following changes and consult your hosting provider to enable htaccess/htpasswd protection:</p>
<p><u><strong>1. Edit this file:</strong></u><br /><br />%s</p>
<p>Remove the following lines if they exist:</p>
<p><i>%s</i></p>
<p><u><strong>2. Delete this file:</strong></u><br /><br />%s</p>
EOT;
const HTPASSWD_SECURED = <<<'EOT'
<strong>Additional Protection With htaccess/htpasswd</strong>
<p>This CE Phoenix Administration Tool installation is additionally secured through htaccess/htpasswd means.</p>
EOT;
const HTPASSWD_PERMISSIONS = <<<'EOT'
<strong>Additional Protection With htaccess/htpasswd</strong>
<p>This CE Phoenix Administration Tool installation is not additionally secured through htaccess/htpasswd means.</p>
<p>The following files need to be writable by the web server to enable the htaccess/htpasswd security layer:</p>
<ul>
<li>%s</li>
<li>%s</li>
</ul>
<p>Reload this page to confirm if the correct file permissions have been set.</p>
EOT;

const IMAGE_INSERT_NEW_ADMIN = 'New Admin User';
