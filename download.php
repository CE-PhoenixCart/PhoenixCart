<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

// Upgrade notices, warnings, etc. to fatal errors
// This prevents notices from appearing in the body of the file.
  set_error_handler(function ($severity, $message, $file, $line, $unused = null) {
    if (error_reporting() & $severity) {
      throw new ErrorException($message, 0, $severity, $file, $line);
    }
  });
  ob_start();

  include 'includes/application_top.php';

  if (ob_get_contents()) {
    error_log(sprintf('Unneeded output:  [%s]', ob_get_clean()));
  }

  ob_end_clean();

  if (!isset($_SESSION['customer_id'])) {
    die();
  }

// Check download.php was called with proper GET parameters
  if (!is_numeric($_GET['order'] ?? null) || !is_numeric($_GET['id'] ?? null) ) {
    die();
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
    die();
  }
  $downloads = $downloads_query->fetch_assoc();
  $path = DIR_FS_CATALOG . "download/{$downloads['orders_products_filename']}";

  if (!file_exists($path)) {
    die();
  }

// Now decrement counter
  $db->query("UPDATE orders_products_download SET download_count = download_count-1 WHERE orders_products_download_id = " . (int)$_GET['id']);

  if (DOWNLOAD_BY_REDIRECT === 'true') {
// This will work only on Unix/Linux hosts, may be blocked by security restrictions,
// doesn't actually enforce limits until a second person tries to download,
// and has some race conditions (e.g. if two people try to download at the same time).
// This is very efficient on server resources though.
    redirect_downloader::link($path, $downloads['orders_products_filename']);
  }


// Now send the file with header() magic
  header('Expires: Mon, 26 Nov 1962 00:00:00 GMT');
  header('Last-Modified: ' . gmdate('D,d M Y H:i:s') . ' GMT');
  header('Cache-Control: no-cache, must-revalidate');
  header('Pragma: no-cache');
  header('Content-Type: Application/octet-stream');
  header('Content-disposition: attachment; filename="' . $downloads['orders_products_filename'] . '"');

// Fallback to readfile() delivery method. This will work on all systems, but will need considerable resources
  readfile($path);
