<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $order = new order($oID);
  $address = $customer_data->get_module('address');
  $email_address = $customer_data->get('email_address', $order->customer);

  $orders_statuses = order_status::fetch_options();
?>

  <div class="row">
    <div class="col">
      <h1 class="display-4 mb-2"><?= sprintf(HEADING_TITLE_ORDER, (int)$oID) ?></h1>
    </div>
    <div class="col-12 col-lg-8 text-start text-lg-end align-self-center pb-1">
      <?=
       $Admin->button(GET_HELP, '', 'btn-dark me-2', GET_HELP_LINK, ['newwindow' => true]),
       $admin_hooks->cat('extraButtons'),
       $Admin->button(IMAGE_ORDERS_INVOICE, 'fas fa-file-invoice-dollar', 'btn-info me-2', $Admin->link('invoice.php')->set_parameter('oID', $_GET['oID']), ['newwindow' => true]),
       $Admin->button(IMAGE_ORDERS_PACKINGSLIP, 'fas fa-file-contract', 'btn-info me-2', $Admin->link('packingslip.php')->set_parameter('oID', $_GET['oID']), ['newwindow' => true]),
       $Admin->button(IMAGE_BACK, 'fas fa-angle-left', 'btn-light', $Admin->link('orders.php')->retain_query_except(['action']))
      ?>
    </div>
  </div>


  <div id="orderTabs">
    <ul class="nav nav-tabs">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#section_summary_content" role="tab"><?= TAB_TITLE_SUMMARY ?></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#section_products_content" role="tab"><?= TAB_TITLE_PRODUCTS ?></a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#section_status_history_content" role="tab"><?= TAB_TITLE_STATUS_HISTORY ?></a></li>
    </ul>

    <div class="tab-content pt-3">
      <div class="tab-pane fade show active" id="section_summary_content" role="tabpanel">
        <table class="table">
          <thead class="table-dark">
            <tr>
              <th><?= ENTRY_CUSTOMER ?></th>
              <th><?= ENTRY_SHIPPING_ADDRESS ?></th>
              <th><?= ENTRY_BILLING_ADDRESS ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <p><?= $address->format($order->customer, 1, '', '<br>') ?></p>
                <p><?= $customer_data->get('telephone', $order->customer) . '<br><a href="mailto:' . $email_address . '"><u>' . $email_address . '</u></a>' ?></p>
              </td>
              <td><p><?= $order->delivery ? $address->format($order->delivery, 1, '', '<br>') : TEXT_NO_DELIVERY_ADDRESS ?></p></td>
              <td><p><?= $address->format($order->billing, 1, '', '<br>') ?></p></td>
            </tr>
          </tbody>
          <thead class="table-dark">
            <tr>
              <th><?= ENTRY_PAYMENT_METHOD ?></th>
              <th><?= ENTRY_STATUS ?></th>
              <th><?= ENTRY_TOTAL ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><p><?= sprintf(TEXT_ORDER_PAYMENT, $order->info['payment_method'], $order->info['date_purchased']) ?></p></td>
              <td><p><?= sprintf(TEXT_ORDER_STATUS, $order->info['orders_status'], ($order->info['last_modified'] ?? $order->info['date_purchased'])) ?></p></td>
              <td><h1 class="display-4"><?= $order->info['total'] ?></h1></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="section_products_content" role="tabpanel">
        <table class="table table-striped">
          <thead class="table-dark">
            <tr>
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
            Guarantor::ensure_global('currencies');
            foreach ($order->products as $product) {
              echo '<tr>';
                echo '<td>' . $product['qty'] . ' x ' . $product['name'];
                if (!empty($product['attributes'])) {
                  foreach ($product['attributes'] as $attribute) {
                    echo '<br><small> - ' . $attribute['option'] . ': ' . $attribute['value'];
                    if ($attribute['price'] != '0') {
                      echo ' (' . $attribute['prefix'] . $currencies->format($attribute['price'] * $product['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                    }
                    echo '</small>';
                  }
                }
                echo '</td>';
                echo '<td>' . $product['model'] . '&nbsp;</td>';
                echo '<td class="text-end">' . Tax::format($product['tax']) . '%</td>';
                echo '<td class="text-end">' . $currencies->format($product['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>';
                echo '<td class="text-end">' . $currencies->format(Tax::add($product['final_price'], $product['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</td>';
                echo '<td class="text-end">' . $currencies->format($product['final_price'] * $product['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</strong></td>';
                echo '<th class="text-end">' . $currencies->format(Tax::add($product['final_price'], $product['tax']) * $product['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</th>';
              echo '</tr>';
            }
            foreach ($order->totals as $ot) {
              echo '<tr>';
                echo '<td colspan="6" class="text-end bg-white">' . $ot['title'] . '</td>';
                echo '<th class="text-end bg-white">' . $ot['text'] . '</th>';
              echo '</tr>';
            }
            ?>
          </tbody>
        </table>

      </div>
      <div class="tab-pane fade" id="section_status_history_content" role="tabpanel">
        <?= new Form('status', $Admin->link('orders.php')->retain_query_except()->set_parameter('action', 'update_order')) ?>

          <div class="row mb-2" id="zStatus">
            <label for="oStatus" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_STATUS ?></label>
            <div class="col-sm-9">
              <?= new Select('status', $orders_statuses, ['value' => $order->info['orders_status_id'], 'id' => 'oStatus', 'class' => 'form-select']) ?>
            </div>
          </div>

          <div class="row mb-2" id="zComment">
            <label for="oComment" class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_NOTIFY_COMMENTS ?></label>
            <div class="col-sm-9">
              <?= new Textarea('comments', ['cols' => '60', 'rows' => '5', 'id' => 'oComment', 'class' => 'form-control']) ?>
            </div>
          </div>

          <div class="row mb-2 align-items-center" id="zNotify">
            <div class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_NOTIFY_CUSTOMER ?></div>
            <div class="col-sm-9 ps-5">
              <div class="form-check form-switch">
                <?= (new Tickable('notify', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'oNotify'], 'checkbox'))->tick() ?>
                <label for="oNotify" class="form-check-label text-muted"><small><?= ENTRY_NOTIFY_CUSTOMER_TEXT ?></small></label>
              </div>
            </div>
          </div>

          <div class="row mb-2 align-items-center" id="zNotifyComments">
            <div class="col-form-label col-sm-3 text-start text-sm-end"><?= ENTRY_NOTIFY_COMMENTS ?></div>
            <div class="col-sm-9 ps-5">
              <div class="form-check form-switch">
                <?= (new Tickable('notify_comments', ['value' => 'on', 'class' => 'form-check-input', 'id' => 'oNotifyComments'], 'checkbox'))->tick() ?>
                <label for="oNotifyComments" class="form-check-label text-muted"><small><?= ENTRY_NOTIFY_COMMENTS_TEXT ?></small></label>
              </div>
            </div>
          </div>

          <?= $admin_hooks->cat('sectionStatusHistoryContentForm') ?>
          
          <div class="d-grid my-2">
            <?= new Button(IMAGE_SAVE, 'fas fa-save', 'btn-success') ?>
          </div>

        </form>

        <table class="table table-striped">
          <thead class="table-dark">
            <tr>
              <th><?= TABLE_HEADING_DATE_ADDED ?></th>
              <th><?= TABLE_HEADING_STATUS ?></th>
              <th><?= TABLE_HEADING_COMMENTS ?></th>
              <th class="text-end"><?= TABLE_HEADING_CUSTOMER_NOTIFIED ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $orders_history_query = $db->query("SELECT * FROM orders_status_history WHERE orders_id = " . (int)$oID . " ORDER BY date_added DESC");
            if (mysqli_num_rows($orders_history_query)) {
              $orders_status_dictionary = array_column($orders_statuses, 'text', 'id');
              while ($orders_history = $orders_history_query->fetch_assoc()) {
                echo '<tr>';
                  echo '<td>' . $orders_history['date_added'] . '</td>';
                  echo '<td>' . $orders_status_dictionary[$orders_history['orders_status_id']] . '</td>';
                  echo '<td>' . nl2br(htmlspecialchars($orders_history['comments'])) . '&nbsp;</td>';
                  echo '<td class="text-end">';
                    echo ($orders_history['customer_notified'] == '1') ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>';
                  echo '</td>';
                echo '</tr>' . "\n";
              }
            } else {
              echo '<tr>';
                echo '<td colspan="4">' . TEXT_NO_ORDER_HISTORY . '</td>';
              echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </div>

      <?= $admin_hooks->cat('orderTab') ?>

    </div>
  </div>
