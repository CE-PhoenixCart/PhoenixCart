<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

const HEADING_TITLE = 'Database Backup Manager';

const TABLE_HEADING_TITLE = 'Title';
const TABLE_HEADING_FILE_DATE = 'Date';
const TABLE_HEADING_FILE_SIZE = 'Size';
const TABLE_HEADING_ACTION = 'Action';

const TEXT_INFO_HEADING_NEW_BACKUP = 'New Backup';
const TEXT_INFO_HEADING_RESTORE_LOCAL = 'Restore Local';
const TEXT_INFO_NEW_BACKUP = 'Do not interrupt the backup process which might take a couple of minutes.';
const TEXT_INFO_UNPACK = '<br /><br />(after unpacking the file from the archive)';
const TEXT_INFO_RESTORE = 'Do not interrupt the restoration process.<br /><br />The larger the backup, the longer this process takes!<br /><br />If possible, use the mysql client.<br /><br />For example:<br /><br /><strong>mysql -h' . DB_SERVER . ' -u' . DB_SERVER_USERNAME . ' -p ' . DB_DATABASE . ' < %s </strong> %s';
const TEXT_INFO_RESTORE_LOCAL = 'Do not interrupt the restoration process.<br /><br />The larger the backup, the longer this process takes!';
const TEXT_INFO_RESTORE_LOCAL_RAW_FILE = 'The file uploaded must be a raw sql (text) file.';
const TEXT_INFO_DATE = 'Date: %s';
const TEXT_INFO_SIZE = 'Size: %s';
const TEXT_INFO_COMPRESSION = 'Compression: %s';
const TEXT_INFO_USE_GZIP = 'Use GZIP';
const TEXT_INFO_USE_ZIP = 'Use ZIP';
const TEXT_INFO_USE_NO_COMPRESSION = 'No Compression (Pure SQL)';
const TEXT_INFO_DOWNLOAD_ONLY = 'Download only (do not store server side)';
const TEXT_INFO_BEST_THROUGH_HTTPS = 'Best through a HTTPS connection';
const TEXT_DELETE_INTRO = 'Are you sure you want to delete this backup?';
const TEXT_NO_EXTENSION = 'None';
const TEXT_BACKUP_DIRECTORY = 'Backup Directory:<br>%s';
const TEXT_LAST_RESTORATION = 'Last Restoration:<br>%s';
const TEXT_FORGET = 'Forget';

const PHP_DATE_TIME_FORMAT = 'm/d/Y H:i:s'; // this is used for date()

const ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST = '<strong>Error:</strong> Backup directory does not exist. Please set this in configure.php.';
const ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE = '<strong>Error:</strong> Backup directory is not writeable.';
const ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE = '<strong>Error:</strong> Download link not acceptable.';
const ERROR_INVALID_FILE = 'The file [%s] is not recognized as a valid backup.  Verify that it exists, has a .sql extension, and is of a reasonable size.';
const ERROR_FILE_TOO_LARGE = 'The file is "%d" bytes but the limit is "%s".';
const ERROR_PATH_NOT_REMOVEABLE = '<strong>Error:</strong> Not able to remove "%s"';

const SUCCESS_LAST_RESTORE_CLEARED = '<strong>Success:</strong> The last restoration date has been cleared.';
const SUCCESS_DATABASE_SAVED = '<strong>Success:</strong> The database has been saved.';
const SUCCESS_DATABASE_RESTORED = '<strong>Success:</strong> The database has been restored.';
const SUCCESS_BACKUP_DELETED = '<strong>Success:</strong> The backup has been removed.';

const TEXT_INFO_BACKUP_SIZE = '%s MB';

const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Database_Backup';
