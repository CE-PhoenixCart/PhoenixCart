<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  require 'includes/application_top.php';

  $currencies = new currencies();

  $order = new order(Text::input($_GET['oID']));
  $address = $customer_data->get_module('address');

  require 'includes/template_top.php';
?>

  <div class="row align-items-center">
    <div class="col">
      <h1 class="display-4"><?= STORE_NAME ?></h1>
      <p class="fw-bold m-0 p-0"><?= STORE_ADDRESS ?></p>
      <p class="my-1 p-0">
        <?php
        if (!Text::is_empty(STORE_PHONE)) {
          echo '<i class="fas fa-phone fa-fw me-1"></i>' . STORE_PHONE;
        }
        ?>
        <i class="fas fa-at fa-fw me-1"></i><?= STORE_OWNER_EMAIL_ADDRESS ?>
      </p>
      <p class="my-1 p-0"><i class="fas fa-home fa-fw me-1"></i><?= $GLOBALS['Admin']->catalog('') ?></p>
    </div>
    <div class="col text-end">
      <?= $Admin->catalog_image('images/' . STORE_LOGO, ['alt' => STORE_NAME]) ?>
      <h6 class="lead fw-bold m-0"><?= ENTRY_INVOICE ?></h6>
      <?php
      if (!Text::is_empty(STORE_TAX_ID)) {
        echo '<p class="mt-1 mb-2 p-0">' . sprintf(ENTRY_INVOICE_TAX_ID, STORE_TAX_ID) . '</p>';
      }
      ?>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col">
      <ul class="list-group border h-100">
        <li class="list-group-item border-0"><h6 class="lead m-0"><?= ENTRY_SHIP_TO ?></h6></li>
        <li class="list-group-item border-0 fw-bold"><?= $address->format($order->delivery, 1, '', '<br>') ?></li>
      </ul>
    </div>
    <div class="col">
      <ul class="list-group border h-100">
        <li class="list-group-item border-0"><h6 class="lead m-0"><?= ENTRY_SOLD_TO ?></h6></li>
        <li class="list-group-item border-0"><?= $address->format($order->billing, 1, '', '<br>') ?></li>
        <li class="list-group-item border-0">
          <?php
          if (!Text::is_empty($customer_data->get('telephone', $order->customer))) {
            echo '<i class="fas fa-phone fa-fw me-1"></i>', $customer_data->get('telephone', $order->customer), '<br>';
          }
          echo '<i class="fas fa-at fa-fw me-1"></i>', $customer_data->get('email_address', $order->customer);
          ?>
        </li>
     </ul>
    </div>
    <div class="col">
      <ul class="list-group border h-100">
        <li class="list-group-item border-0"><h6 class="lead m-0"><?= sprintf(ENTRY_INVOICE_NUMBER, (int)$_GET['oID']) ?></h6></li>
        <li class="list-group-item border-0"><?= sprintf(ENTRY_INVOICE_DATE, Date::abridge($order->info['date_purchased'])) ?></li>
        <li class="list-group-item border-0"><?= sprintf(ENTRY_PAYMENT_METHOD, $order->info['payment_method']) ?></li>
        <?= $admin_hooks->cat('invoiceData') ?>
      </ul>
    </div>
  </div>

  <table class="table table-striped mt-3">
    <thead class="table-dark">
      <tr>
        <th><?= TABLE_HEADING_QTY ?></th>
        <th><?= TABLE_HEADING_PRODUCTS ?></th>
        <th><?= TABLE_HEADING_PRODUCTS_MODEL ?></th>
        <th class="text-end"><?= TABLE_HEADING_TAX ?></th>
        <th class="text-end"><?= TABLE_HEADING_PRICE_EXCLUDING_TAX ?></th>
        <th class="text-end"><?= TABLE_HEADING_PRICE_INCLUDING_TAX ?></th>
        <th class="text-end"><?= TABLE_HEADING_TOTAL_EXCLUDING_TAX ?></th>
        <th class="text-end"><?= TABLE_HEADING_TOTAL_INCLUDING_TAX ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($order->products as $product) {
        echo '<tr>';
          echo '<td>' . $product['qty'] . '</td>';
          echo '<th>' . $product['name'];
          foreach (($product['attributes'] ?? []) as $attribute) {
            echo '<br><small><i> - ' . $attribute['option'] . ': ' . $attribute['value'];
            if ($attribute['price'] != '0') {
              echo ' (' . $attribute['prefix'] . $currencies->format($attribute['price'] * $product['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
            }
            echo '</i></small>';
          }
          echo '</th>';
          echo '<td>' . $product['model'] . '</td>';
          echo '<td class="text-end">' . Tax::format($product['tax']) . '%</td>';
          echo '<td class="text-end">' . $currencies->format($product['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>';
          echo '<td class="text-end">' . $currencies->format(Tax::add($product['final_price'], $product['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</td>';
          echo '<td class="text-end">' . $currencies->format($product['final_price'] * $product['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>';
          echo '<th class="text-end">' . $currencies->format(Tax::add($product['final_price'], $product['tax']) * $product['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</th>';
        echo '</tr>';
      }

      foreach ($order->totals as $order_total) {
        echo '<tr>';
          echo '<th colspan="7" class="text-end bg-white border-0" scope="row">' . $order_total['title'] . '</th>';
          echo '<th class="text-end bg-white border-0">' . $order_total['text'] . '</th>';
        echo '</tr>';
      }
      ?>
    </tbody>
  </table>

  <?= $admin_hooks->cat('extraComments') ?>

<?php
  require 'includes/template_bottom.php';
  require 'includes/application_bottom.php';
?>