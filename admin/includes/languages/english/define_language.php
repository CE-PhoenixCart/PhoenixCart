<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

const HEADING_TITLE = 'Define Languages';

const TABLE_HEADING_FILES = 'Files';
const TABLE_HEADING_WRITABLE = 'Writable';
const TABLE_HEADING_LAST_MODIFIED = 'Last Modified';

const TEXT_EDIT_NOTE = <<<'EORT'
<p class="lead">Editing Definitions</p>

<p>Each language definition is set as a PHP constant in the following manner:</p>

<p><pre>const TEXT_MAIN = '<span style="background-color: #FFFF99;">This text can be edited. It\'s really easy to do!</span>');</pre></p>

<p>The highlighted text can be edited. As this definition is using single quotes to contain the text, any single quotes within the text definition must be escaped with a backslash (eg, It\'s)</p>

<p>Multiple line entries can be done like</p>

<p><pre>const TEXT_MAIN = <<<'EOT'
<span style="background-color: #FFFF99;">This text can be edited.
 It's really easy to do!</span>
EOT;</pre></p>

<p>Also, it is no longer necessary to escape with a backslash in multiple line entries like this.</p>
EORT;

const TEXT_FILE_DOES_NOT_EXIST = 'File does not exist.';
const TEXT_INFO_DOWNLOAD_ONLY = 'Download only (do not store server side)';

const ERROR_FILE_NOT_WRITEABLE = '<strong>Error:</strong> I can not write to this file, so will download instead of saving. Please set the right user permissions on %s if you want to save in place.';
