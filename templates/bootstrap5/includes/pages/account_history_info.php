<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('account_history.php'));
  $breadcrumb->add(sprintf(NAVBAR_TITLE_3, $_GET['order_id']), $Linker->build('account_history_info.php', ['order_id' => $_GET['order_id']]));

  require $Template->map('template_top.php', 'component');
?>

<div class="row mb-4">
  <div class="col-7"><h1 class="display-4"><?= HEADING_TITLE ?></h1></div>
  <div class="col text-end">
    <p class="fs-4"><?= sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']) . ' <span class="badge text-bg-primary">' . $order->info['orders_status'] . '</span>' ?></p>
    <p><?= '<strong>' . HEADING_ORDER_DATE . '</strong> ' . Date::expound($order->info['date_purchased']) ?></p>
  </div>
</div>

  <div class="row">
    <div class="col-sm-7">
      <table class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th colspan="2"><?= HEADING_PRODUCTS ?></th>
            <?php
  if (count($order->info['tax_groups']) > 1) {
?>
            <th class="text-end"><?= HEADING_TAX ?></th>
              <?php
  }
?>
            <th class="text-end"><?= HEADING_TOTAL ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
  foreach ($order->products as $product) {
    echo '<tr>';
    echo '<td width="30">' . $product['qty'] . '</td>';
    echo '<td>' . $product['name'];
    foreach (($product['attributes'] ?? []) as $attribute) {
      echo '<br><small><i> - ' . $attribute['option'] . ': ' . $attribute['value'] . '</i></small>';
    }
    echo '</td>';

    if (count($order->info['tax_groups']) > 1) {
      echo '<td valign="top" class="text-end">' . Tax::display($product['tax']) . '%</td>';
    }

    echo '<td class="text-end">' . $currencies->format(Tax::price($product['final_price'], $product['tax']) * $product['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>';
    echo '</tr>';
  }
   
  $colspan = 4;
  foreach ($order->totals as $output) {
    include Guarantor::ensure_global('Template')->map('order_total.php', 'component');
  }
?>
        <tbody>
      </table>
    </div>
    <div class="col">
      <div class="border">
        <ul class="list-group list-group-flush">
          <?php
  $address = $customer_data->get_module('address');
  if ($order->delivery) {
    echo '<li class="list-group-item">';
    echo SHIPPING_FA_ICON;
    echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b><br>';
    echo $address->format($order->delivery, 1, ' ', '<br>');
    echo '</li>';
  }
?>
          <li class="list-group-item">
            <?php
  echo PAYMENT_FA_ICON;
  echo '<b>' . HEADING_BILLING_ADDRESS . '</b><br>';
  echo $address->format($order->billing, 1, ' ', '<br>');
?>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <h2 class="fs-4"><?= HEADING_ORDER_HISTORY ?></h2>

  <ul class="list-group">
<?php
  $statuses_query = $db->query(sprintf(<<<'EOSQL'
SELECT os.orders_status_name, osh.date_added, osh.comments
 FROM orders_status os INNER JOIN orders_status_history osh ON osh.orders_status_id = os.orders_status_id
 WHERE os.public_flag = 1 AND osh.orders_id = %d AND os.language_id = %d
 ORDER BY osh.date_added
EOSQL
    , (int)$_GET['order_id'], (int)$_SESSION['languages_id']));
  while ($status = $statuses_query->fetch_assoc()) {
    echo '<li class="list-group-item">';
      echo '<div class="d-flex justify-content-between align-items-center">';
        echo '<p class="fs-6 fw-semibold"">' . $status['orders_status_name'] . '</p>';
        echo '<span class="badge rounded-pill text-bg-primary"><i class="far fa-clock me-1"></i>' . $status['date_added'] . '</span>';
      echo '</div>';
      echo (empty($status['comments']) ? '' : nl2br(htmlspecialchars($status['comments'])));
    echo '</li>';
  }
?>
  </ul>

<?php
  if (DOWNLOAD_ENABLED == 'true') {
    include $Template->map('downloads.php', 'component');
  }

  echo $hooks->cat('orderDetails');
?>

  <div class="my-2">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account_history.php')->retain_query_except(['order_id'])) ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
