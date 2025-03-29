<?php
  error_reporting(E_ALL);
  ini_set('display_errors','1'); 

  const HTTP_SERVER = 'http://localhost';
  const COOKIE_OPTIONS = [
    'lifetime' => 0,
    'domain' => 'localhost',
    'path' => '/PhoenixCart/',
    'samesite' => 'Lax',
  ];
  const DIR_WS_CATALOG = '/PhoenixCart/';

  date_default_timezone_set('Europe/Berlin');

// If you are asked to provide configure.php details,
// please remove the data below before sharing.
  const DIR_FS_CATALOG = 'C:/laragon/www/PhoenixCart/';

  const DB_SERVER = 'localhost';
  const DB_SERVER_USERNAME = 'root';
  const DB_SERVER_PASSWORD = '';
  const DB_DATABASE = 'phoenixcart';
