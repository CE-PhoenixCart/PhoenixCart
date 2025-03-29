<?php
  error_reporting(E_ALL);
  ini_set('display_errors','1'); 

  const HTTP_SERVER = 'http://localhost';
  const COOKIE_OPTIONS = [
    'lifetime' => 0,
    'domain' => 'localhost',
    'path' => '/PhoenixCart/admin',
    'samesite' => 'Lax',
  ];
  const DIR_WS_ADMIN = '/PhoenixCart/admin/';

  const HTTP_CATALOG_SERVER = 'http://localhost';
  const DIR_WS_CATALOG = '/PhoenixCart/';

  date_default_timezone_set('Europe/Berlin');

  const DIR_FS_CATALOG = 'C:/laragon/www/PhoenixCart/';

  const DIR_FS_DOCUMENT_ROOT = 'C:/laragon/www/PhoenixCart/';
  const DIR_FS_ADMIN = 'C:/laragon/www/PhoenixCart/admin/';
  const DIR_FS_BACKUP = DIR_FS_ADMIN . 'backups/';

// If you are asked to provide configure.php details,
// before sharing, please remove the data below and
// obfuscate the admin folder and home directory (DIR_FS).
  const DB_SERVER = 'localhost';
  const DB_SERVER_USERNAME = 'root';
  const DB_SERVER_PASSWORD = '';
  const DB_DATABASE = 'phoenixcart';
