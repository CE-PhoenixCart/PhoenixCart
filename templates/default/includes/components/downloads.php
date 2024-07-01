<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (strstr(Request::get_page(), 'account_history_info.php')) {
    $last_order = $_GET['order_id'];
  } else {
// Get last order id for checkout_success
    $orders = $GLOBALS['db']->query("SELECT orders_id FROM orders WHERE customers_id = " . (int)$_SESSION['customer_id'] . " ORDER BY orders_id DESC LIMIT 1")->fetch_assoc();
    $last_order = $orders['orders_id'];
  }

// Now get all downloadable products in that order
  $downloads_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT
    date_format(o.date_purchased, '%%Y-%%m-%%d') AS date_purchased_day,
    opd.download_maxdays,
    op.products_name,
    opd.orders_products_download_id,
    opd.orders_products_filename,
    opd.download_count,
    opd.download_maxdays
 FROM orders o
   INNER JOIN orders_products op ON o.orders_id = op.orders_id
   INNER JOIN orders_products_download opd ON op.orders_products_id = opd.orders_products_id
   INNER JOIN orders_status os ON o.orders_status = os.orders_status_id
 WHERE opd.orders_products_filename != ''
   AND os.downloads_flag = 1
   AND o.customers_id = %d
   AND o.orders_id = %d
   AND os.language_id = %d
EOSQL
    , (int)$_SESSION['customer_id'], (int)$last_order, (int)$_SESSION['languages_id']));
  if (mysqli_num_rows($downloads_query) > 0) {
?>

    <h5 class="my-2"><?= HEADING_DOWNLOAD ?></h5>

    <table class="table table-borderless">
      <thead class="thead-light">
        <tr>
          <th scope="col"><?= TABLE_HEADING_DOWNLOAD_PRODUCT ?></th>
          <th scope="col"><?= TABLE_HEADING_DOWNLOAD_FILE ?></th>
          <th scope="col" ><?= TABLE_HEADING_DOWNLOAD_COUNT ?></th>
          <th scope="col" class="text-right"><?= TABLE_HEADING_DOWNLOAD_DATE ?></th>
        </tr>
      </thead>
      <tbody>

<?php
    while ($downloads = $downloads_query->fetch_assoc()) {
      
// MySQL 3.22 does not have INTERVAL
      list($dt_year, $dt_month, $dt_day) = explode('-', $downloads['date_purchased_day']);
      $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);
      $download_expiry = date('Y-m-d H:i:s', $download_timestamp);

      echo '<tr>';
        echo '<th>' . $downloads['products_name'] . '</th>' . PHP_EOL;

// The link will appear only if:
// - Download remaining count is > 0, AND
// - The file is present in the DOWNLOAD directory, AND EITHER
// - No expiry date is enforced (maxdays == 0), OR
// - The expiry date is not reached
        if ( ($downloads['download_count'] > 0) && (file_exists(DIR_FS_CATALOG . 'download/' . $downloads['orders_products_filename'])) && ( ($downloads['download_maxdays'] == 0) || ($download_timestamp > time())) ) {
          echo '<td>', new Button($downloads['orders_products_filename'], 'fas fa-download', 'btn btn-sm btn-block btn-dark', [], $GLOBALS['Linker']->build('download.php', ['order' => $last_order, 'id' => $downloads['orders_products_download_id']])), '</td>' . PHP_EOL;
        } else {
          echo '<td>' . $downloads['orders_products_filename'] . '</td>' . PHP_EOL;
        }

        echo '<td>' . $downloads['download_count'] . '</td>';
        echo '<td class="text-right">' . Date::expound($download_expiry) . '</td>';
      echo '</tr>';
    }
?>

      </tbody>
    </table>

<?php
    if (!strstr(Request::get_page(), 'account_history_info.php')) {
?>

    <p><?= sprintf(FOOTER_DOWNLOAD, '<a href="' . $GLOBALS['Linker']->build('account.php') . '">' . HEADER_TITLE_MY_ACCOUNT . '</a>') ?></p>

<?php
    }
  }
?>
