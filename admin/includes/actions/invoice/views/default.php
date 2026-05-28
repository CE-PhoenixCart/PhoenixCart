<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2026 Phoenix Cart

  Released under the GNU General Public License
*/
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

  <?php
  $table_definition = [
    'data' => $order->products,
    'columns' => [
      [
        'name' => TABLE_HEADING_QTY,
        'function' => fn($row) => $row['qty'],
      ],
      [
        'name' => TABLE_HEADING_PRODUCTS,
        'class' => 'fw-bold',
        'function' => function ($row) use ($currencies, $order) {
          $output = htmlspecialchars($row['name'], ENT_QUOTES | ENT_HTML5);
          foreach (($row['attributes'] ?? []) as $attribute) {
            $output .= '<br><small><i> - ' .  htmlspecialchars($attribute['option'], ENT_QUOTES | ENT_HTML5) . ': ' .  htmlspecialchars($attribute['value'], ENT_QUOTES | ENT_HTML5);
            if ($attribute['price'] != '0') {
              $output .= ' (' . $attribute['prefix']
                      . $currencies->format($attribute['price'] * $row['qty'], true, $order->info['currency'], $order->info['currency_value'])
                      . ')';
            }
            $output .= '</i></small>';
          }
          return $output;
        },
      ],
      [
        'name' => TABLE_HEADING_PRODUCTS_MODEL,
        'function' => fn($row) =>  htmlspecialchars($row['model'], ENT_QUOTES | ENT_HTML5),
      ],
      [
        'name' => TABLE_HEADING_TAX,
        'class' => 'text-end',
        'function' => fn($row) => Tax::format($row['tax']) . '%',
      ],
      [
        'name' => TABLE_HEADING_PRICE_EXCLUDING_TAX,
        'class' => 'text-end',
        'function' => fn($row) => $currencies->format($row['final_price'], true, $order->info['currency'], $order->info['currency_value']),
      ],
      [
        'name' => TABLE_HEADING_PRICE_INCLUDING_TAX,
        'class' => 'text-end',
        'function' => fn($row) => $currencies->format(
          Tax::add($row['final_price'], $row['tax']), true, $order->info['currency'], $order->info['currency_value']),
      ],
      [
        'name' => TABLE_HEADING_TOTAL_EXCLUDING_TAX,
        'class' => 'text-end',
        'function' => fn($row) => $currencies->format($row['final_price'] * $row['qty'], true, $order->info['currency'], $order->info['currency_value']),
      ],
      [
        'name' => TABLE_HEADING_TOTAL_INCLUDING_TAX,
        'class' => 'text-end fw-bold',
        'function' => fn($row) => $currencies->format(
          Tax::add($row['final_price'], $row['tax']) * $row['qty'], true, $order->info['currency'], $order->info['currency_value']),
      ],
    ],
    'style' => 'table-striped mt-3',
  ];
  
  $table = new Table($table_definition);

  $total_columns = count($table->table_definition['columns']);
  $left_colspan = $total_columns - 1;

  $footer_rows = [];
  foreach ($order->totals as $order_total) {
    $footer_rows[] = [
      [
        'tag' => 'th', 
        'attributes' => "colspan=\"$left_colspan\" class=\"text-end border-0\"", 
        'content' =>  htmlspecialchars($order_total['title'], ENT_QUOTES | ENT_HTML5)
      ],
      [
        'tag' => 'th', 
        'attributes' => "class=\"text-end border-0\"", 
        'content' => strip_tags($order_total['text'])
      ],
    ];
  }

  $table->table_definition['tfoot'] = $footer_rows;

  $table->display_table();
  ?>

  <?= $admin_hooks->cat('extraComments') ?>
  