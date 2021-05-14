<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  $breadcrumb->add(NAVBAR_TITLE_1, $Linker->build('account.php'));
  $breadcrumb->add(NAVBAR_TITLE_2, $Linker->build('account_history.php'));

  require $Template->map('template_top.php', 'component');
?>

<h1 class="display-4"><?= HEADING_TITLE ?></h1>

<?php
  if ($customer->count_orders() > 0) {
    $history_sql = sprintf(<<<'EOSQL'
SELECT o.*, ot.text AS order_total, s.orders_status_name
 FROM orders o INNER JOIN orders_total ot ON o.orders_id = ot.orders_id INNER JOIN orders_status s ON o.orders_status = s.orders_status_id
 WHERE ot.class = 'ot_total' AND s.public_flag = 1 AND s.language_id = %d AND o.customers_id = %d
 ORDER BY orders_id DESC
EOSQL
      , (int)$_SESSION['languages_id'], (int)$_SESSION['customer_id']);
    $history_split = new splitPageResults($history_sql, MAX_DISPLAY_ORDER_HISTORY);
    $history_query = $db->query($history_split->sql_query);
?>
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <caption class="sr-only"><?= $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS) ?></caption>
        <thead class="thead-dark">
          <tr>
            <th scope="col"><?= TEXT_ORDER_NUMBER ?></th>
            <th scope="col" class="d-none d-md-table-cell"><?= TEXT_ORDER_STATUS ?></th>
            <th scope="col"><?= TEXT_ORDER_DATE ?></th>
            <th scope="col" class="d-none d-md-table-cell"><?= TEXT_ORDER_PRODUCTS ?></th>
            <th scope="col"><?= TEXT_ORDER_COST ?></th>
            <th class="text-right" scope="col"><?= TEXT_VIEW_ORDER ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $order_link = $Linker->build('account_history_info.php')->retain_parameters();
          while ($history = $history_query->fetch_assoc()) {
            $products = $db->query("SELECT SUM(products_quantity) AS count FROM orders_products WHERE orders_id = " . (int)$history['orders_id'])->fetch_assoc();
            $order_link->set_parameter('order_id', (int)$history['orders_id'])
            ?>
            <tr>
              <th scope="row"><?= $history['orders_id'] ?></th>
              <td class="d-none d-md-table-cell"><?= $history['orders_status_name'] ?></td>
              <td><?= Date::abridge($history['date_purchased']) ?></td>
              <td class="d-none d-md-table-cell"><?= $products['count'] ?></td>
              <td><?= strip_tags($history['order_total']) ?></td>
              <td class="text-right"><?= new Button(BUTTON_VIEW_ORDER, '', 'btn-primary btn-sm', [], $order_link) ?></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
    </div>

    <div class="row align-items-center">
      <div class="col-sm-6 d-none d-sm-block">
        <?= $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS) ?>
      </div>
      <div class="col-sm-6">
        <?= $history_split->display_links(MAX_DISPLAY_PAGE_LINKS) ?>
      </div>
    </div>

<?php
  } else {
?>

  <div class="alert alert-info" role="alert">
    <p><?= TEXT_NO_PURCHASES ?></p>
  </div>

<?php
  }
?>

  <div class="buttonSet my-2">
    <?= new Button(IMAGE_BUTTON_BACK, 'fas fa-angle-left', 'btn-light', [], $Linker->build('account.php')) ?>
  </div>

<?php
  require $Template->map('template_bottom.php', 'component');
?>
