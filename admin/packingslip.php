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

  $oID = Text::input($_GET['oID']);

  $order = new order($oID);
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
    <div class="col text-end"><?= $Admin->catalog_image('images/' . STORE_LOGO, ['alt' => STORE_NAME]) ?></div>
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
            echo '<i class="fas fa-phone fa-fw"></i>', $customer_data->get('telephone', $order->customer), '<br>';
          }
          echo '<i class="fas fa-at fa-fw"></i>', $customer_data->get('email_address', $order->customer);
          ?>
        </li>
     </ul>
    </div>
    <div class="col">
      <ul class="list-group border h-100">
        <li class="list-group-item border-0"><h6 class="lead m-0"><?= sprintf(ENTRY_INVOICE_NUMBER, (int)$_GET['oID']) ?></h6></li>
        <li class="list-group-item border-0"><?= sprintf(ENTRY_INVOICE_DATE, Date::abridge($order->info['date_purchased'])) ?></li>
        <li class="list-group-item border-0"><?= sprintf(ENTRY_PAYMENT_METHOD, $order->info['payment_method']) ?></li>
      </ul>
    </div>
  </div>

  <table class="table table-striped mt-3">
    <thead class="table-dark">
      <tr>
        <th><?= TABLE_HEADING_QTY ?></th>
        <th><?= TABLE_HEADING_PRODUCTS ?></th>
        <th><?= TABLE_HEADING_PRODUCTS_MODEL ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach ($order->products as $product) {
          echo '<tr>';
            echo '<td>' . $product['qty'] . '</td>';
            echo '<th>' . $product['name'];
            foreach (($product['attributes'] ?? []) as $attribute) {
              echo '<br><small><i> - ' . $attribute['option'] . ': ' . $attribute['value'] . '</i></small>';
            }
            echo '</th>';
            echo '<td>' . $product['model'] . '</td>';
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