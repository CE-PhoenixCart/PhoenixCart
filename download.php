<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  include 'includes/application_top.php';

  if (!isset($_SESSION['customer_id'])) {
    die;
  }

// Check download.php was called with proper GET parameters
  if (!is_numeric($_GET['order'] ?? null) || !is_numeric($_GET['id'] ?? null) ) {
    die;
  }

  $downloads_query = $db->query(sprintf(<<<'EOSQL'
SELECT opd.orders_products_filename
  FROM orders o
    INNER JOIN orders_products op ON o.orders_id = op.orders_id
    INNER JOIN orders_products_download opd ON op.orders_products_id = opd.orders_products_id
    INNER JOIN orders_status os ON o.orders_status = os.orders_status_id
  WHERE opd.orders_products_filename != ''
    AND os.downloads_flag = 1
    AND (opd.download_maxdays = 0 OR o.date_purchased >= DATE_SUB(NOW(), INTERVAL opd.download_maxdays DAY))
    AND opd.download_count > 0
    AND o.customers_id = %d
    AND o.orders_id = %d
    AND opd.orders_products_download_id = %d
    AND os.language_id = %d
EOSQL
    , (int)$_SESSION['customer_id'], (int)$_GET['order'], (int)$_GET['id'], (int)$_SESSION['languages_id']));
  if (!mysqli_num_rows($downloads_query)) {
    die;
  }
  $downloads = $downloads_query->fetch_assoc();

  if (!file_exists(DIR_FS_CATALOG . 'download/' . $downloads['orders_products_filename'])) {
    die;
  }

// Now decrement counter
  $db->query("UPDATE orders_products_download SET download_count = download_count-1 WHERE orders_products_download_id = " . (int)$_GET['id']);

// Returns a random name, 16 to 20 characters long
// There are more than 10^28 combinations
// The directory is "hidden", i.e. starts with '.'
function phoenix_random_name() {
  $letters = 'abcdefghijklmnopqrstuvwxyz';

  $dirname = '.';
  $length = mt_rand(16, 20);
  for ($i = 1; $i <= $length; $i++) {
    $dirname .= $letters[random_int(0, 25)];
  }

  return $dirname;
}

// Unlinks all subdirectories and files in $dir
// Works only on one subdir level, will not recurse
function phoenix_unlink_temp_dir($dir) {
  $h1 = opendir($dir);
  while ($subdir = readdir($h1)) {
// Ignore . and .. and CVS and non directories
    if (in_array($subdir, ['.', '..', 'CVS']) || !is_dir("$dir$subdir")) {
      continue;
    }
// Loop and unlink files in subdirectory
    $h2 = opendir($dir . $subdir);
    while ($file = readdir($h2)) {
      if ($file === '.' || $file === '..') continue;
      @unlink("$dir$subdir/$file");
    }
    closedir($h2);
    @rmdir($dir . $subdir);
  }
  closedir($h1);
}


// Now send the file with header() magic
  header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream");
  header("Content-disposition: attachment; filename=" . $downloads['orders_products_filename']);

  if (DOWNLOAD_BY_REDIRECT == 'true') {
// This will work only on Unix/Linux hosts
    phoenix_unlink_temp_dir('pub/');
    $tempdir = phoenix_random_name();
    umask(0000);
    mkdir('pub/' . $tempdir, 0777);
    $file = "pub/$tempdir/{$downloads['orders_products_filename']}";
    symlink(DIR_FS_CATALOG . 'download/' . $downloads['orders_products_filename'], $file);
    if (file_exists($file = "pub/$tempdir/{$downloads['orders_products_filename']}")) {
      Href::redirect($Linker->build($file, [], false));
    }
  }

// Fallback to readfile() delivery method. This will work on all systems, but will need considerable resources
  readfile(DIR_FS_CATALOG . 'download/' . $downloads['orders_products_filename']);
?>
