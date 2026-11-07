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

        <?php
        Guarantor::ensure_global('currencies');
            
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
  
        $table = new tableLayout($table_definition);

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
