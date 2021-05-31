<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $db_server = trim($_POST['DB_SERVER']);
  $db_username = trim($_POST['DB_SERVER_USERNAME']);
  $db_password = trim($_POST['DB_SERVER_PASSWORD']);
  $db_database = trim($_POST['DB_DATABASE']);
  $db = new Database($db_server, $db_username, $db_password, $db_database)
    or die('No database connection');

  installer::configure('STORE_NAME', Text::sanitize($_POST['CFG_STORE_NAME']));
  installer::configure('STORE_OWNER', Text::sanitize($_POST['CFG_STORE_OWNER_NAME']));
  installer::configure('STORE_OWNER_EMAIL_ADDRESS', Text::sanitize($_POST['CFG_STORE_OWNER_EMAIL_ADDRESS']));

  $dir_fs_document_root = rtrim($_POST['DIR_FS_DOCUMENT_ROOT'], '/\\');

  if ( !empty($_POST['CFG_ADMINISTRATOR_USERNAME']) ) {
    $db->query(sprintf(<<<'EOSQL'
INSERT INTO administrators (user_name, user_password) VALUES ('%s', '%s')
 ON DUPLICATE KEY UPDATE user_password = VALUES(user_password)
EOSQL
      , $db->escape(Text::sanitize($_POST['CFG_ADMINISTRATOR_USERNAME'])),
        Password::hash(trim($_POST['CFG_ADMINISTRATOR_PASSWORD']))));
  }


  $writable_directory = "$dir_fs_document_root/includes/work/";
  installer::configure('DIR_FS_CACHE', Text::sanitize($writable_directory));
  installer::configure('SESSION_WRITE_DIRECTORY', Text::sanitize($writable_directory));

  if ($handle = opendir($writable_directory)) {
    while (false !== ($filename = readdir($handle))) {
      if ('cache' === pathinfo($filename, PATHINFO_EXTENSION)) {
        @unlink("$writable_directory$filename");
      }
    }

    closedir($handle);
  }

  $http_url = parse_url($_POST['HTTP_WWW_ADDRESS']);
  $http_server = $http_url['scheme'] . '://' . $http_url['host'];
  $http_catalog = $http_url['path'];
  if (!empty($http_url['port'])) {
    $http_server .= ':' . $http_url['port'];
  }

  if (substr($http_catalog, -1) !== '/') {
    $http_catalog .= '/';
  }

  $secure = ('on' === getenv('HTTPS'))
          ? "\n    'secure' => true,"
          : '';

  $admin_folder = 'admin';
  if (!empty($_POST['CFG_ADMIN_DIRECTORY']) && Path::is_writable($dir_fs_document_root) && Path::is_writable("$dir_fs_document_root/admin")) {
    $admin_folder = preg_replace('{[^a-zA-Z0-9]}', '', trim($_POST['CFG_ADMIN_DIRECTORY'])) ?: 'admin';
  }

  $time_zone = isset($_POST['CFG_TIME_ZONE'])
             ? "'" . trim($_POST['CFG_TIME_ZONE']) . "'"
             : 'date_default_timezone_get()';

  $sharing_warning = TEXT_SHARING_WARNING;

  $file_contents = <<<"EOPHP"
<?php
  error_reporting(E_ALL);

  const HTTP_SERVER = '$http_server';
  const COOKIE_OPTIONS = [
    'lifetime' => 0,
    'domain' => '{$http_url['host']}',
    'path' => '$http_catalog',
    'samesite' => 'Lax',$secure
  ];
  const DIR_WS_CATALOG = '$http_catalog';

  date_default_timezone_set($time_zone);

$sharing_warning
  const DIR_FS_CATALOG = '$dir_fs_document_root/';

  const DB_SERVER = '$db_server';
  const DB_SERVER_USERNAME = '$db_username';
  const DB_SERVER_PASSWORD = '$db_password';
  const DB_DATABASE = '$db_database';

EOPHP;

  Installer::burn("$dir_fs_document_root/includes/configure.php", $file_contents);

  $sharing_warning = TEXT_SHARING_WARNING_ADMIN;

  $file_contents = <<<"EOPHP"
<?php
  error_reporting(E_ALL);

  const HTTP_SERVER = '$http_server';
  const COOKIE_OPTIONS = [
    'lifetime' => 0,
    'domain' => '{$http_url['host']}',
    'path' => '$http_catalog$admin_folder',
    'samesite' => 'Lax',$secure
  ];
  const DIR_WS_ADMIN = '$http_catalog$admin_folder/';

  const HTTP_CATALOG_SERVER = '$http_server';
  const DIR_WS_CATALOG = '$http_catalog';

  date_default_timezone_set($time_zone);

  const DIR_FS_CATALOG = '$dir_fs_document_root/';

  const DIR_FS_DOCUMENT_ROOT = '$dir_fs_document_root/';
  const DIR_FS_ADMIN = '$dir_fs_document_root/$admin_folder/';
  const DIR_FS_BACKUP = DIR_FS_ADMIN . 'backups/';

$sharing_warning
  const DB_SERVER = '$db_server';
  const DB_SERVER_USERNAME = '$db_username';
  const DB_SERVER_PASSWORD = '$db_password';
  const DB_DATABASE = '$db_database';

EOPHP;

  Installer::burn("$dir_fs_document_root/admin/includes/configure.php", $file_contents);

  if ($admin_folder !== 'admin') {
    @rename("$dir_fs_document_root/admin", "$dir_fs_document_root/$admin_folder");
  }
?>

<div class="row">
  <div class="col-sm-9">
    <div class="alert alert-info" role="alert">
      <h1><?= TEXT_FINISHED ?></h1>

      <p><?= TEXT_FINISHED_EXPLANATION ?></p>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="card mb-2">
      <div class="card-body">
        <ol>
          <li class="text-muted"><?= TEXT_DATABASE_SERVER ?></li>
          <li class="text-muted"><?= TEXT_WEB_SERVER ?></li>
          <li class="text-muted"><?= TEXT_STORE_SETTINGS ?></li>
          <li class="text-success"><strong><?= TEXT_FINISHED ?></strong></li>
        </ol>
      </div>
      <div class="text-footer">
        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">100%</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="w-100"></div>

<div class="row">
  <div class="col-12 col-sm-9">
    <div class="row">
      <div class="col"><?= new Button(TEXT_ADMIN, 'fas fa-lock mr-2', 'btn-info btn-block', ['newwindow' => 1], "$http_server$http_catalog$admin_folder/index.php") ?></div>
      <div class="col"><?= new Button(TEXT_STORE, 'fas fa-shopping-cart mr-2', 'btn-success btn-block', ['newwindow' => 1], "$http_server{$http_catalog}index.php") ?></div>
      <div class="col"><?= new Button('<img src="images/icon_phoenix.png" class="mr-2">' . TEXT_FORUM, '', 'btn-dark btn-block', ['newwindow' => 1], 'https://phoenixcart.org/forum/') ?></div>
    </div>
  </div>

  <div class="col-12 col-sm-3">
    <h3 class="display-4"><?= TEXT_STEP_4 ?></h3>
    <div class="card mb-2">
      <div class="card-body">
        <?= TEXT_STEP_4_EXPLANATION ?>
        <p><?= new Button('<img src="images/icon_phoenix.png" class="mr-2">' . TEXT_FORUM, '', 'btn-dark btn-block', ['newwindow' => 1], 'https://phoenixcart.org/forum/') ?></p>
      </div>
      <div class="card-footer">
        - <a class="card-link" href="https://phoenixcart.org/forum/" target="_blank" rel="noreferrer"><?= TEXT_TEAM ?></a>
      </div>
    </div>
  </div>
</div>
